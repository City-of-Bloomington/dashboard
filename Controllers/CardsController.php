<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\Card;
use Application\Models\CardsTable;

use Blossom\Classes\Block;
use Blossom\Classes\Controller;

class CardsController extends Controller
{
	public function index(array $params)
	{
        $table = new CardsTable();
        $list  = $table->find();
        return new \Application\Views\Cards\ListView(['cards'=>$list]);
	}

	public function view(array $params)
	{
        if (!empty($_GET['id'])) {
            try { $card = new Card($_GET['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        return isset($card)
            ? new \Application\Views\Cards\InfoView(['card'=>$card])
            : new \Application\Views\NotFoundView();
	}

	public function update(array $params)
	{
        if (!empty($_REQUEST['id'])) {
            try { $card = new Card($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else {
            $card = new Card();
        }

        if (!empty($_REQUEST['service_id'])) { $card->setService_id($_REQUEST['service_id']); }
        if (!empty($_REQUEST['method'    ])) { $card->setMethod    ($_REQUEST['method'    ]); }

        if (isset($card)) {
            if (isset($_POST['description'])) {
                try {
                    $card->handleUpdate($_POST);
                    $card->save();
                    header('Location: '.parent::generateUrl('cards.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }

            return new \Application\Views\Cards\UpdateView(['card'=>$card]);
        }
        else { return new \Application\Views\NotFoundView(); }
	}

	public function delete(array $params)
	{
        if (!empty($_GET['id'])) {
            try { $card = new Card($_GET['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        if (isset($card)) {
            $card->delete();
            header('Location: '.parent::generateUrl('cards.index'));
            exit();
        }
        else { return new \Application\Views\NotFoundView(); }
	}
}
