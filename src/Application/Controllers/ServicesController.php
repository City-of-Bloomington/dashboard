<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\Service;
use Application\Models\ServicesTable;

use Blossom\Classes\Block;
use Blossom\Classes\Controller;

class ServicesController extends Controller
{
    public function index(array $params)
    {
        $table = new ServicesTable();
        $list  = $table->find();
        return new \Application\Views\Services\ListView(['services'=>$list]);
    }

    public function view(array $params)
    {
        try { $service = new Service($_GET['id']); }
        catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }

        if (isset($service)) {
            return new \Application\Views\Services\InfoView(['service'=>$service]);
        }
        else { return new \Application\Views\NotFoundView(); }
    }

    public function update(array $params)
    {
        if (isset($_REQUEST['id'])) {
            try { $service = new Service($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else {
            $service = new Service();
        }

        if (isset($service)) {
            if (isset($_POST['name'])) {
                try {
                    $service->handleUpdate($_POST);
                    $service->save();

                    $url = parent::generateUrl('services.index');
                    header("Location: $url");
                    exit();
                }
                catch (\Exception $e) {
                    $_SESSION['errorMessages'][] = $e;
                }
            }

            return new \Application\Views\Services\UpdateView(['service'=>$service]);
        }
        else { return new \Application\Views\NotFoundView(); }
    }
}
