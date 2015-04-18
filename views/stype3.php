
<table>
  <colgroup>
    <col span="1">
    <col span="2">
  </colgroup>
  <thead>
    <tr>
      <th><?php echo $question_number++ ?>. <?php echo $data['questions'][0] ?></th>
      <th>R</th>
      <th>%</th>
    </tr>
  </thead>
  <tbody>
<?php $max = 0;
      foreach ($data['summary'] as $summary):
          $max = (($summary[0] > $max) ? $summary[0] : $max);
      endforeach; ?>
<?php foreach ($data['summary'] as $ks => $summary): ?>
    <tr>
      <td><?php echo $data['answers'][$ks] ?></td>
<?php if ($summary[0] == $max):
          $tag1 = '<strong>';
          $tag2 = '</strong>';
      else:
          $tag1 = '';
          $tag2 = '';
      endif; ?>
      <td><?php echo $tag1, $summary[0], $tag2 ?></td>
      <td><?php echo $tag1, $summary[1], $tag2 ?></td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>
