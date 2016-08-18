<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\CardsTable;
use Blossom\Classes\Controller;

class CardsController extends Controller
{
	public function index(array $params)
	{
        $table = new CardsTable();
        $list  = $table->find();
        return new \Application\Views\Cards\ListView(['cards'=>$list]);
	}

	public function view()
	{
        if (!empty($_REQUEST['id'])) {
            try { $card = new Card($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        if (isset($card)) {
            $this->template->blocks[] = new Block('cards/info.inc', ['card'=>$card]);
        }
        else { $this->do404(); }
	}

	public function update()
	{
        if (!empty($_REQUEST['id'])) {
            try { $card = new Card($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else {
            $card = new Card();
        }

        if (isset($card)) {
            if (isset($_POST['id'])) {
                try {
                    $card->handleUpdate($_POST);
                    $card->save();
                    header('Location: '.parent::generateUrl('cards.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }

            $this->template->blocks[] = new Block('cards/updateForm.inc', ['card'=>$card]);
        }
        else { $this->do404(); }
	}

	public function delete()
	{
        if (!empty($_REQUEST['id'])) {
            try { $card = new Card($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        if (isset($card)) {
            $card->delete();
            header('Location: '.parent::generateUrl('cards.index'));
            exit();
        }
        else { $this->do404(); }
	}

    private function do404()
    {
        header('HTTP/1.1 404 Not Found', true, 404);
        $this->template->blocks[] = new Block('404.inc');
    }

}
