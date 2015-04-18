<?php $colspan = count($data['answers']) + 1 ?>
<table>
  <colgroup>
    <col span="1">
    <col span="<?php echo $colspan * 2 ?>">
  </colgroup>
  <thead>
    <tr>
      <th rowspan="2"><?php echo (isset($data['help']) ? $data['help'] : '')?></th>
      <th colspan="<?php echo $colspan ?>">Responses</th>
      <th colspan="<?php echo $colspan ?>">Percentage</th>
    </tr>
    <tr>
<?php foreach ($data['answers'] as $answer): ?>
      <th><?php echo $answer ?></th>
<?php endforeach; ?>
      <th>Tot.</th>
<?php foreach ($data['answers'] as $answer): ?>
      <th><?php echo $answer ?></th>
<?php endforeach; ?>
      <th>Tot.</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($data['summary'] as $ks => $summary): ?>
    <tr>
      <td><?php echo $question_number++ ?>. <?php echo $data['questions'][$ks] ?></td>
<?php $half = count($summary) / 2;
      $max1 = max(array_slice($summary, 0, $half));
      $max2 = max(array_slice($summary, $half - 1, $half)); ?>
<?php foreach ($summary as $ke => $element): ?>
<?php if (($ke <= $half - 1 && $element == $max1) || $ke > $half - 1 && $element == $max2):
          $tag1 = '<strong>';
          $tag2 = '</strong>';
      else:
          $tag1 = '';
          $tag2 = '';
      endif; ?>
      <td><?php echo $tag1, $element, $tag2 ?></td>
<?php if ($ke == $colspan - 2): ?>
      <td><?php echo $response_count ?></td>
<?php endif; ?>
<?php endforeach; ?>
      <td>100</td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>

