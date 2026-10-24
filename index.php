<?php
require_once __DIR__ . '/random.php';
header('Cache-Control: no-store');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Randoms</title>
<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
	font-family: Courier New;
}

th, td {
    padding: 4px;
}
</style>

</head>

<body>
  <table style="width:500px">
	<thead>
	  <tr>
        <th>6</th>
        <th>7</th>
        <th>8</th>
        <th>9</th>
        <th>18</th>
        <th>32</th>
	  </tr>
	</thead>

	<tbody>
	  <tr>
	    <td><?php echo random_str(6);?></td>
	    <td><?php echo random_str(7);?></td>
	    <td><?php echo random_str(8);?></td>
	    <td><?php echo random_str(9);?></td>
	    <td><?php echo random_str(18);?></td>
	    <td><?php echo random_str(32);?></td>
	  </tr>
	  <?php for ($l = 0; $l <= 10; $l++) {
		echo "<tr>";
		echo "<td>".random_str(6)."</td>";
		echo "<td>".random_str(7)."</td>";
		echo "<td>".random_str(8)."</td>";
		echo "<td>".random_str(9)."</td>";
		echo "<td>".random_str(18)."</td>";
		echo "<td>".random_str(32)."</td>";
		echo "</tr>";
		} ?>
	</tbody>
  </table>

</body>
</html>

