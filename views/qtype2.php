<fieldset class="type2">
  <legend><?php echo $number ?>. <?php echo $data['questions'][0]?></legend>
  <input type="hidden" name="survey_data[<?php echo $name ?>][responses][0]" value="0">
<?php foreach ($data['answers'] as $a_index => $answer): ?>
  <input type="radio" id="Q<?php echo $number ?><?php echo $a_index ?>" name="survey_data[<?php echo $name ?>][responses][0]" value="<?php echo ($a_index + 1)?>"<?php echo (($data['responses'][0] == $a_index + 1) ? ' checked' : '')?>>
  <label for="Q<?php echo $number ?><?php echo $a_index ?>"><?php echo $answer ?></label><?php echo (($a_index + 1 < count($data['answers'])) ? '<br>' : '')?>

<?php endforeach; ?>
</fieldset>

