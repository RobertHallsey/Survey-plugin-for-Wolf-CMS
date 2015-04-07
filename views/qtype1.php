<table>
	<thead>
		<tr><th><?php echo (isset($help) ? $help : '') ?></th><?php foreach ($data['answers'] as $a_index => $answer): ?><th><?php echo $answer ?></th><?php endforeach; ?></tr>
	</thead>
	<tbody>
<?php foreach ($data['questions'] as $q_index => $question): ?>
		<tr>
			<td><?php echo $number ?>. <?php echo $question ?><input type="hidden" name="survey_data[<?php echo$name ?>][responses][<?php echo $q_index ?>]" value="0"></td>
<?php foreach ($data['answers'] as $a_index => $answer): ?>
<?php $a_index++; ?>
			<td><input type="radio" title="<?php echo $question ?>: <?php echo $answer ?>" name="survey_data[<?php echo $name ?>][responses][<?php echo $q_index ?>]" value="<?php echo $a_index ?>"<?php echo (($data['responses'][$q_index] == $a_index) ? ' checked' : '') ?>></td>
<?php echo (($a_index < count($data['answers'])) ? '': '		</tr>' . "\n")?>
<?php endforeach; ?>
<?php $number++; ?>
<?php endforeach; ?>
	</tbody>
</table>

