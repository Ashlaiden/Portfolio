<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\View;
use App\Models\URI;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class ViewLogger implements FilterInterface
{
    /**
     * @throws ReflectionException
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Get Session ID
        $session    = \Config\Services::session();
        $sessionId  = session_id();

        // 2. IP
        $ip = $request->getIPAddress();

        // 3. Device Info
        $agent  = $request->getUserAgent();
        $device = $agent->getPlatform() . ' - ' . $agent->getBrowser();

        // 4. GeoIP Country
        $geo     = @unserialize(file_get_contents("http://ip-api.com/php/{$ip}?fields=country"));
        $country = $geo['country'] ?? 'Unknown';

        // 5. URI Logic
        $uri         = current_url();
        if (str_contains($uri, 'admin')) {
            return;
        }
        $uri_model   = new \App\Models\URI();
        $uri_obj     = $uri_model->where('uri', $uri)->first();

        // If URI not found, create it
        if (!$uri_obj) {
            $uri_model->save(['uri' => $uri]);
            $uri_obj = $uri_model->where('uri', $uri)->first(); // re-fetch for ID
        }

        // 6. Check last view
        $viewModel = new \App\Models\View();
        $last      = $viewModel
            ->where('session_id', $sessionId)
            ->where('ip_address', $ip)
            ->orderBy('created_at', 'DESC')
            ->first();

        // default JSON array
        $page_ids = [];

        if ($last) {
            $page_ids = json_decode($last['page_id'], true);

            // fallback if not array
            if (!is_array($page_ids)) {
                $page_ids = [];
            }

            // already viewed? bounce
            if (in_array($uri_obj['id'], $page_ids)) {
                return;
            }

            // not viewed yet, update it
            $page_ids[] = $uri_obj['id'];
            $page_ids = array_unique($page_ids);

            $viewModel->update($last['id'], [
                'page_id' => json_encode($page_ids),
            ]);

            // increment view count
            $uri_model->update($uri_obj['id'], [
                'view_count' => $uri_obj['view_count'] + 1,
            ]);

            return; // stop here — don’t save a whole new view
        }

        // 7. Save new view if no prior record found
        $page_ids[] = $uri_obj['id'];
//        var_dump($uri_obj['id']);
//        echo '<br>';
//        var_dump($page_ids);
//        echo '<br>';
//        var_dump(json_encode($page_ids));
//        echo '<br>';

        $viewModel->save([
            'session_id'  => $sessionId,
            'ip_address'  => $ip,
            'device_name' => $device,
            'country'     => $country,
            'page_id'     => json_encode($page_ids),
        ]);
//        var_dump($viewModel->where('session_id', $sessionId)->first());

        $uri_model->update($uri_obj['id'], [
            'view_count' => $uri_obj['view_count'] + 1,
        ]);
    }


    // ← Notice: NO type hint on $arguments
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing (or any teardown logic)
    }
}
