<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Pages';

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Genetic_Unword');

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 */
	public function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));
	}

	public function dictionary()
	{
		if(isset($_POST['nword']) && isset($_POST['nclassification']))
		{
			if($this->Genetic_Unword->find('count', array('conditions'=>array('Genetic_Unword.word like'=>$_POST['nword']))) == 0)
			{
				$new = array('Genetic_Unword'=>array('word'=>$_POST['nword'], 'word_desc'=>$_POST['nclassification']));
				$this->Genetic_Unword->save($new);
			}
			else
			{
				$this->set('exists', 'This word exists already.');
			}
		}

		if(isset($_POST['eword']) && isset($_POST['eclassification']) && isset($_POST['eid']))
		{
			$eclass = $_POST['eclassification'];
			$eword  = $_POST['eword'];
			$eid    = $_POST['eid'];
			$this->Genetic_Unword->updateAll(array('Genetic_Unword.word'=>"'$eword'", 'Genetic_Unword.word_desc'=>"'$eclass'"), array('Genetic_Unword.id'=>$eid));
		}

		$all_words = $this->Genetic_Unword->find('all');
		$count     = $this->Genetic_Unword->find('count');

		$this->set('words', $all_words);
		$this->set('wordcount', $count);
	}

	public function search()
	{
		if(isset($_POST['search']) && !empty($_POST['search']))
		{
			$search = preg_replace("#[^A-Za-z0-9]#i", '', $_POST['search']);
			$count = $this->Genetic_Unword->find('count', array('conditions'=>array('Genetic_Unword.word like'=>$search)));

			if($count == 0)
			{
				die('{"message":"This word does not exist." }');
			}
			else
			{
				$details = $this->Genetic_Unword->find('first', array('conditions'=>array('Genetic_Unword.word like'=>$search)));
				die('{"message":"This word exists with the id number '. $details['Genetic_Unword']['id'] .'" }');
			}
		}
		else
		{
			die('{"message":"Please enter the word." }');
		}
	}

	/*public function addIllegal()
	{
		$illegal = "May:Linking Verb, Might:Linking Verb, Must:Linking Verb, Be:Linking Verb, Being:Linking Verb, Been:Linking Verb, Am:Linking Verb, Are:Linking Verb, Is:Linking Verb, Was:Linking Verb, Were:Linking Verb, Do:Linking Verb, Does:Linking Verb, Did:Linking Verb, Should:Linking Verb, Could:Linking Verb, Would:Linking Verb, Have:Linking Verb, Had:Linking Verb, Has:Linking Verb, Will:Linking Verb, Can:Linking Verb, Shall:Linking Verb";

		$illegal_words = explode(',', $illegal);

		foreach ($illegal_words as $key => $value) {
			$illegal_word = explode(':', $value);

			if($this->Genetic_Unword->find('count', array('conditions'=>array('Genetic_Unword.word'=>$illegal_word[0]))) == 0)
			{
				$word = strtolower($illegal_word[0]);
				$add_illegal = array('Genetic_Unword'=>array('word'=>$word, 'word_desc'=>$illegal_word[1]));
				$this->Genetic_Unword->save($add_illegal);
				$this->Genetic_Unword->create();
			}
		}
		die();
	}*/
	
}
