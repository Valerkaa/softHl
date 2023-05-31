<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Helper softik</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


        <script>
        $(document).ready(function() {
            $('.select-box').on('change', function() {
                var selectedValue = $(this).val();

                if (selectedValue !== "default" && selectedValue !== "writeIt") {
                    $('#host, #usr, #pass').prop('readonly', true);
                } else {
                    $('#host, #usr, #pass').prop('readonly', false);
                }
            });
        });
    </script>

</head>
<body>
<div class="flex-container">
	<div class="spinner"><p>
		<div class="cube1"></div>
		<div class="cube2"></div>
		Loading...
		</p>
	</div>
	<div class="flex-slide home">
		<div class="flex-title flex-title-home">Залив архивов</div>
		<div class="flex-about flex-about-home">
					<form class="contact-form" action="zaliv-archive-api.php" method="post" enctype="multipart/form-data">
						<p>Host IP <input type="text" name="host" id="host" class="text-field"></p>
						<p>Login <input type="text" name="usr" id="usr" class="text-field"></p>
						<p>Password <input type="text" name="pass" id="pass" class="text-field"></p>
						<p>Select  <select id="selectOption1" name="select" class="select-box">
						<option value="default" selected disabled>Выбери значение</option>
						<option value="writeIt">Написать свой</option>
            <?php

                $connect = new mysqli("localhost", "soft_usr", "n5eLcJ3xazRDTR1g", "soft");

                // Проверка подключения к базе данных
                if ($connect->connect_error) {
                    die("Ошибка подключения: " . $connect->connect_error);
                }

                // Выполнение запроса к базе данных
                $sql = "SELECT * FROM Panels";
                $result = $connect->query($sql);

                // Заполнение элемента <select> значениями из базы данных
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value=\"" . $row['IP'] . "\" data-host=\"" . $row['IP'] . "\" data-usr=\"" . $row['login'] . "\" data-pass=\"" . $row['pass'] . "\">" . $row['IP'] . "</option>";
                    }
                }

                // Закрытие соединения с базой данных
                $connect->close();
            ?>
        </select><br><br></p>
						<p style="text-align: center">Не более 10 штук за раз</p>
						<p>Domains <textarea type="text" name="domains" row="5" placeholder="Домены из таблицы сюда"></textarea></p>
						
						 <input type="file" name="fileToUpload" id="fileToUpload">
						<p><input type="submit"  value="Отправить на залив"></p>
					</form>
        </div>
	</div>
	<div class="flex-slide about">
		<div class="flex-title">Залив карточек</div>
		<div class="flex-about">

				<form class="contact-form" action="zaliv-cart-api.php" method="post" >
						<p>Host IP <input type="text" name="host" id="host" class="text-field"></p>
						<p>Login <input type="text" name="usr" id="usr" class="text-field"></p>
						<p>Password <input type="text" name="pass" id="pass" class="text-field"></p>
						<p style="text-align: center">Не более 10 штук за раз</p>
							<p>Select  <select id="selectOption2" name="select" class="select-box">
						<option value="default" selected disabled>Выбери значение</option>
						<option value="writeIt">Написать свой</option>
            <?php

                $connect = new mysqli("localhost", "soft_usr", "n5eLcJ3xazRDTR1g", "soft");

                // Проверка подключения к базе данных
                if ($connect->connect_error) {
                    die("Ошибка подключения: " . $connect->connect_error);
                }

                // Выполнение запроса к базе данных
                $sql = "SELECT * FROM Panels";
                $result = $connect->query($sql);

                // Заполнение элемента <select> значениями из базы данных
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value=\"" . $row['IP'] . "\" data-host=\"" . $row['IP'] . "\" data-usr=\"" . $row['login'] . "\" data-pass=\"" . $row['pass'] . "\">" . $row['IP'] . "</option>";
                    }
                }

                // Закрытие соединения с базой данных
                $connect->close();
            ?>
        </select><br><br>
						<p>Domains <textarea type="text" name="domains" row="5" placeholder="Домены из таблицы сюда" required></textarea></p>
						<p><input type="submit"  value="Отправить на залив"></p>
					</form>
        </div>
	</div>
	<div class="flex-slide work">
		<div class="flex-title">Создание поддомена</div>
		<div class="flex-about">
					<form class="contact-form" action="create-poddomen.php" method="post">
						<p>Host IP <input type="text" name="host" id="host" class="text-field"></p>
						<p>Login <input type="text" name="usr" id="usr" class="text-field"></p>
						<p>Password <input type="text" name="pass" id="pass" class="text-field"></p>
							<p>Select  <select id="selectOption3" name="select" class="select-box">
						<option value="default" selected disabled>Выбери значение</option>
						<option value="writeIt">Написать свой</option>
            <?php

                $connect = new mysqli("localhost", "soft_usr", "n5eLcJ3xazRDTR1g", "soft");

                // Проверка подключения к базе данных
                if ($connect->connect_error) {
                    die("Ошибка подключения: " . $connect->connect_error);
                }

                // Выполнение запроса к базе данных
                $sql = "SELECT * FROM Panels";
                $result = $connect->query($sql);

                // Заполнение элемента <select> значениями из базы данных
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value=\"" . $row['IP'] . "\" data-host=\"" . $row['IP'] . "\" data-usr=\"" . $row['login'] . "\" data-pass=\"" . $row['pass'] . "\">" . $row['IP'] . "</option>";
                    }
                }

                // Закрытие соединения с базой данных
                $connect->close();
            ?>
        </select><br><br>
						<p style="text-align: center">Не более 10 штук за раз</p>
						<p>dirDomain <input type="text" name="dirDomain" class="text-field"></p>
						<p>Domains <textarea type="text" name="domains" row="5" placeholder="Поддомены из таблицы сюда" required></textarea></p>
						<p><input type="submit"  value="Отправить на залив"></p>
					</form>
		</div>
	</div>
	<div class="flex-slide contact">
		<div class="flex-title">Залив на поддомен</div>
				<div class="flex-about">
				<form class="contact-form" action="zaliv-poddomen.php" method="post" enctype="multipart/form-data">
						<p>Host IP <input type="text" name="host" id="host" class="text-field"></p>
						<p>Login <input type="text" name="usr" id="usr" class="text-field"></p>
						<p>Password <input type="text" name="pass" id="pass" class="text-field"></p>
							<p>Select  <select id="selectOption4" name="select" class="select-box">
						<option value="default" selected disabled>Выбери значение</option>
						<option value="writeIt">Написать свой</option>
            <?php

                $connect = new mysqli("localhost", "soft_usr", "n5eLcJ3xazRDTR1g", "soft");

                // Проверка подключения к базе данных
                if ($connect->connect_error) {
                    die("Ошибка подключения: " . $connect->connect_error);
                }

                // Выполнение запроса к базе данных
                $sql = "SELECT * FROM Panels";
                $result = $connect->query($sql);

                // Заполнение элемента <select> значениями из базы данных
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value=\"" . $row['IP'] . "\" data-host=\"" . $row['IP'] . "\" data-usr=\"" . $row['login'] . "\" data-pass=\"" . $row['pass'] . "\">" . $row['IP'] . "</option>";
                    }
                }

                // Закрытие соединения с базой данных
                $connect->close();
            ?>
        </select><br><br>
						<p style="text-align: center">Не более 10 штук за раз</p>
                        <input type="file" name="fileToUploads" id="fileToUploads">
						<p>Sub Domains <textarea type="text" name="domains" row="5" placeholder="Поддомены из таблицы сюда" required></textarea></p>
						<p><input type="submit"  value="Отправить на залив"></p>
					</form>

		</div>
	</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/769286/jquery.waitforimages.min.js"></script>
<script src="main.js"></script>
<script>
// Get all select boxes with class 'select-box'
const selectBoxes = document.getElementsByClassName('select-box');

// Function to handle select change
function handleSelectChange(selectBox) {
  const selectedOption = selectBox.options[selectBox.selectedIndex];
  const form = selectBox.closest('form');  // Get the parent form of the select box

  // Get the input fields within the same form
  const hostInput = form.querySelector('input[name="host"]');
  const usrInput = form.querySelector('input[name="usr"]');
  const passInput = form.querySelector('input[name="pass"]');

  // Extract data from selected option
  const host = selectedOption.getAttribute('data-host');
  const usr = selectedOption.getAttribute('data-usr');
  const pass = selectedOption.getAttribute('data-pass');

  // Set the values of the input fields
  hostInput.value = host;
  usrInput.value = usr;
  passInput.value = pass;
}

// Assign event listener to all select boxes
for (let i = 0; i < selectBoxes.length; i++) {
  selectBoxes[i].addEventListener('change', function() {
    handleSelectChange(selectBoxes[i]);
  });
}
</script>
</body>
</html>