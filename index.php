<?php
require_once __DIR__ . '/random.php';
header('Cache-Control: no-store');
$lengths = [6, 7, 8, 9, 18, 32];
$rowCount = 12;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Random Password Generator</title>
<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
	font-family: Courier New;
}

th, td {
    padding: 4px;
}

td {
    vertical-align: top;
}

textarea {
    display: block;
    font-family: "Courier New", monospace;
    line-height: 1.5;
    resize: vertical;
    white-space: pre;
}

button {
    margin-top: 4px;
}
</style>

</head>

<body>
  <p>Each field contains one column of passwords. Select a column and copy.</p>
  <table>
	<thead>
	  <tr>
        <?php foreach ($lengths as $length): ?>
        <th scope="col"><label for="passwords-<?php echo $length; ?>"><?php echo $length; ?></label></th>
        <?php endforeach; ?>
	  </tr>
	</thead>

	<tbody>
	  <tr>
        <?php foreach ($lengths as $length): ?>
        <?php
        $passwords = [];
        for ($row = 0; $row < $rowCount; ++$row) {
            $passwords[] = random_str($length);
        }
        ?>
        <td>
          <textarea id="passwords-<?php echo $length; ?>" name="passwords-<?php echo $length; ?>" rows="<?php echo $rowCount; ?>" cols="<?php echo $length; ?>" readonly wrap="off" spellcheck="false" autocomplete="off"><?php echo htmlspecialchars(implode("\n", $passwords), ENT_QUOTES, 'UTF-8'); ?></textarea>
          <button type="button" onclick="this.previousElementSibling.focus(); this.previousElementSibling.select();" aria-controls="passwords-<?php echo $length; ?>">Select column</button>
        </td>
        <?php endforeach; ?>
	  </tr>
	</tbody>
  </table>

</body>
</html>

