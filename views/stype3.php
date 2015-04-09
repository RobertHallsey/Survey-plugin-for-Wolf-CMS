
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
<?php foreach ($data['summary'] as $ks => $summary): ?>
  <tr>
    <td><?php echo $data['answers'][$ks] ?></td>
    <td><?php echo $summary[0] ?></td>
    <td><?php echo $summary[1] ?></td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
