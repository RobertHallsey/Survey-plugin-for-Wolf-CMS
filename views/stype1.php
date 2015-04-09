<?php $colspan = count($data['answers']) + 1 ?>

<table>
  <colgroup><col span="1"><col span="<?php echo $colspan * 2 ?>"></colgroup>
  <thead>
    <tr>
      <th rowspan="2"><?php echo (isset($data['help']) ? $data['help'] : '')?></th>
      <th colspan="<?php echo $colspan ?>">Responses</th>
      <th colspan="<?php echo $colspan ?>">Percentage</th>
    </tr>
    <tr>
<?php $temp_string = '' ?>
<?php foreach ($data['answers'] as $answer): ?>
<?php $temp_string .= $answer . '</th><th>' ?>
<?php endforeach; ?>
<?php $temp_string = substr($temp_string, 0, -4) ?>
      <th class="not-1st-child"><?php echo $temp_string ?><th>Tot.</th>
      <?php foreach ($data['answers'] as $answer): ?><th><?php echo $answer ?></th><?php endforeach; ?><th>Tot.</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($data['summary'] as $ks => $summary): ?>
    <tr>
      <td><?php echo $question_number++ ?>. <?php echo $data['questions'][$ks] ?></td>
<?php foreach ($summary as $kse => $summary_element): ?>
      <td><?php echo $summary_element ?></td>
<?php $temp_string = (($kse == $colspan - 2) ? '<td>' . $response_count . '</td>' . "\n" : '') ?>
<?php if ($temp_string): ?>
      <?php echo $temp_string ?>
<?php endif; ?>
<?php endforeach; ?>
      <td>100</td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>
