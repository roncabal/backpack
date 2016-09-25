<?php

/**
* 
*/
class GeneticAlgorithm extends AppController
{
	
	public $uses = array('Genetic_Word', 'Genetic_Unword', 'Genetic_Category');

	public function genetics($files)
	{
		$classification = array();

		foreach ($files as $key => $file) {
			$file_name = strtolower(preg_replace('#[^A-Za-z0-9]#i', ' ', substr($file, 0, strrpos($file, "."))));
			$file_type = strtolower(substr($file, strrpos($file, '.') + 1));

			$categories = $this->Genetic_Category->find('first', array('conditions'=>array('Genetic_Category.file_type'=>$file_type)));
			$file_category = $categories['Genetic_Category']['category'];
			$words = explode(" ", $file_name);
			$with_classification = false;
			foreach ($words as $key => $word) {
				$unqualified_words = $this->Genetic_Unword->find('all');
				$is_credible = true;
				foreach ($unqualified_words as $key => $unword) {
					if(preg_match("/" . $unword['Genetic_Unword']['word'] . "/" , strtolower($word)))
					{
						$is_credible = false;
						break;
					}
				}

				if($is_credible)
				{
					$word_classification_count = $this->Genetic_Word->find('count', array('conditions'=>array('Genetic_Word.gen_files like'=>'%' . $word . '%')));
					$final_word_classification = $this->selection($file, $word, $word_classification_count);
					
					if($final_word_classification !== false)
					{
						$with_classification = true;
						if(!array_key_exists($file_category, $classification))
						{
							$classification[$file_category] = array();
						}

						if(!array_key_exists($final_word_classification, $classification[$file_category]))
						{
							$classification[$file_category][$final_word_classification] = array();
						}

						$file_exists = false;
						foreach ($classification[$file_category][$final_word_classification] as $key => $words_in_classification) {
							if($words_in_classification == $file)
							{
								$file_exists = true;
								break;
							}
						}

						if($file_exists == false)
						{
							array_push($classification[$file_category][$final_word_classification], $file);
						}
					}
				}
			}

			if($with_classification == false)
			{
				if(!array_key_exists($file_category, $classification))
				{
					$classification[$file_category] = array();
				}

				if(!array_key_exists($file_category, $classification[$file_category]))
				{
					$classification[$file_category][$file_category] = array();
				}

				$file_exists = false;
				foreach ($classification[$file_category][$file_category] as $key => $words_in_classification) {
					if($words_in_classification == $file)
					{
						$file_exists = true;
						break;
					}
				}

				if($file_exists == false)
				{
					array_push($classification[$file_category][$file_category], $file);
				}
			}

		}

		//Crossover
		$to_unset = array();
		foreach ($classification as $classification_key => $classification_category) {
			foreach ($classification_category as $category_key => $category_class) {
				foreach ($category_class as $class_key => $class_value) {
					foreach ($classification_category as $category_compare_key => $category_compare_class) {
						foreach ($category_compare_class as $compare_key => $compare_value) {
							if($category_compare_key == $category_key)
							{
								break;
							}

							$cancel = false;
							if(array_key_exists($classification_key, $to_unset)){
								if(array_key_exists($category_key, $to_unset[$classification_key])){
									foreach ($to_unset[$classification_key][$category_key] as $to_unset_key => $to_unset_value)
									{
										if($to_unset_value == $compare_value)
										{
											$cancel = true;
											break;
										}
									}
								}
							}

							if($cancel == true)
							{
								break;
							}

							if($class_value == $compare_value)
							{
								if(!array_key_exists($classification_key, $to_unset))
								{
									$to_unset[$classification_key] = array();
								}

								if(count($classification[$classification_key][$category_key]) >= count($classification[$classification_key][$category_compare_key]))
								{
									if(!array_key_exists($category_compare_key, $to_unset[$classification_key]))
									{
										$to_unset[$classification_key][$category_compare_key] = array();
									}
									array_push($to_unset[$classification_key][$category_compare_key], $compare_key);
								}
								else
								{
									if(!array_key_exists($category_key, $to_unset[$classification_key]))
									{
										$to_unset[$classification_key][$category_key] = array();
									}
									array_push($to_unset[$classification_key][$category_key], $class_key);
								}

							}
						}
					}
				}
			}
		}
		
		foreach ($to_unset as $unset_category_key => $unset_category_value) {
			foreach ($unset_category_value as $unset_class_key => $unset_class_value) {
				foreach ($unset_class_value as $key => $value) {
					unset($classification[$unset_category_key][$unset_class_key][$value]);
				}

				if(count($classification[$unset_category_key][$unset_class_key]) <= 1)
				{
					unset($classification[$unset_category_key][$unset_class_key]);
				}

				if(count($classification[$unset_category_key]) == 0)
				{
					unset($classification[$unset_category_key]);
				}
			}
		}

		return $classification;
	}

	private function selection($file, $word, $word_classification_count)
	{
		if($word_classification_count > 0)
		{
			$word_classifications = $this->Genetic_Word->find('all', array('conditions'=>array('Genetic_Word.gen_files like'=>'%' . $word . '%')));
			$temp_words = array();

			foreach ($word_classifications as $key => $word_classification) {
				$wc = $word_classification['Genetic_Word']['gen_class']; //Gets classification of a row
				$files_in_classifications = explode(';', $word_classification['Genetic_Word']['gen_files']); //Put all file names from a class to an array
				
				$count_match = 0;
				foreach ($files_in_classifications as $files_in_classification){
					if(preg_match("/" . $word . "/", strtolower($files_in_classification)))
					{
						$count_match++;
					}
				}

				$temp_word['classification'] = $wc;
				$temp_word['match'] = $count_match;

				array_push($temp_words, $temp_word);
			}

			$final_word_classification = '';
			$final_word_match = 0;
			foreach ($temp_words as $temp_word){

				if($temp_word['match'] > $final_word_match)
				{
					$final_word_classification = ucfirst($temp_word['classification']);
					$final_word_match = $temp_word['match'];
				}
			}

			
			return $final_word_classification;
		}
		else
		{
			return false;
		}

	}

	public function mutation($classification, $files)
	{
		$classification = strtolower(preg_replace('#[^A-Za-z0-9 ]#i', ' ', $classification));
		$classification_in_words = explode(' ', $classification);
		if(strlen($classification) > 50)
		{
			die('{"status":"success"}');
		}

		//Illegal words
		foreach ($classification_in_words as $word) {
			$word_detail = $this->Genetic_Unword->find('count', array('conditions'=>array('Genetic_Unword.word'=>$word, 'Genetic_Unword.word_desc'=>'illegal')));
			if($word_detail > 0)
			{
				die('{"status":"success"}');
			}
		}

		$files_to_add = array();
		//Check file category
		foreach ($files as $key => $file) {
			$file_type = substr($file, strrpos($file, '.') + 1);
			$file_category = $this->Genetic_Category->find('first', array('conditions'=>array('Genetic_Category.file_type'=>$file_type)));

			if(!array_key_exists($file_category['Genetic_Category']['category'], $files_to_add))
			{
				$files_to_add[$file_category['Genetic_Category']['category']] = array();
			}

			array_push($files_to_add[$file_category['Genetic_Category']['category']], $file);
		}

		foreach ($files_to_add as $files_category_key => $files_category_value) {
			$all_files = '';
			foreach ($files_category_value as $file_key => $file_value) {
				$file_name = preg_replace('#[^A-Za-z0-9]#i', ' ', substr($file_value, 0, strrpos($file_value, '.')));
				$file_type = substr($file_value, strrpos($file_value, '.') + 1);

				$all_files .= $file_name . '.' . $file_type . ';';
			}

			$all_files = rtrim($all_files, ';');
			$classification_exist = $this->Genetic_Word->find('count', array('conditions'=>array('Genetic_Word.gen_class'=>$classification, 'Genetic_Word.gen_category'=>$files_category_key)));

			if($classification_exist <= 0)
			{
				$new_class = array('Genetic_Word'=>array('gen_class'=>$classification, 'gen_files'=>$all_files, 'gen_category'=>$files_category_key));
				$this->Genetic_Word->save($new_class);
				$this->Genetic_Word->create();
			}
			else
			{
				$classification_details = $this->Genetic_Word->find('first', array('conditions'=>array('Genetic_Word.gen_class like'=>$classification, 'Genetic_Word.gen_category'=>$files_category_key)));
				$add_files = $classification_details['Genetic_Word']['gen_files'] . ';' . $all_files;
				$this->Genetic_Word->updateAll(array('Genetic_Word.gen_files'=>"'$add_files'"), array('Genetic_Word.id'=>$classification_details['Genetic_Word']['id']));
			}
		}

		die('{"status":"success"}');
	}

}