<table>
  <thead>
    <tr>
      <th><?php echo $data['help']; ?></th>
<?php foreach ($data['answers'] as $a_index => $answer): ?>
      <th><?php echo $answer ?></th>
<?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
<?php foreach ($data['questions'] as $q_index => $question): ?>
    <tr>
      <td><?php echo $number ?>. <?php echo $question ?><input type="hidden" name="survey_data[<?php echo$name ?>][responses][<?php echo $q_index ?>]" value="0"></td>
<?php foreach ($data['answers'] as $a_index => $answer): ?>
      <td><input type="radio" aria-label="<?php echo $question ?>: <?php echo $answer ?>" name="survey_data[<?php echo $name ?>][responses][<?php echo $q_index ?>]" value="<?php echo $a_index ?>"<?php echo (($data['responses'][$q_index] == $a_index + 1) ? ' checked' : '') ?>></td>
<?php endforeach; ?>
    </tr>
<?php $number++; ?>
<?php endforeach; ?>
  </tbody>
</table>

