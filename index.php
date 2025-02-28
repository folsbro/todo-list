<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/assets/css/reset.css">
  <link rel="stylesheet" href="/assets/css/style.css">
  <title>Задачи</title>
</head>

<body>
  <div class="wrapper">
    <div class="todos-top">
      <a href="/" class="todos-top__logo">
        <img src="/assets/img/roket.svg" alt="">
        <h4>Зада<span>чи</span></h4>
      </a>
    </div>
    <div class="todos-main">
      <?php
      session_start();
      if (!isset($_SESSION['tasks'])) {
        $_SESSION['tasks'] = [];
      }

      $errors = [];
      $completedTasksCount = 0;

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add-task'])) {
          $taskText = trim($_POST['text']);
          if (empty($taskText)) {
            $errors[] = 'Текст задачи не может быть пустым';
          } else {
            $_SESSION['tasks'][] = ['text' => $taskText, 'completed' => false];
          }
        } elseif (isset($_POST['toggle-task-status'])) {
          $taskId = intval($_POST['id']);
          if (isset($_SESSION['tasks'][$taskId])) {
            $_SESSION['tasks'][$taskId]['completed'] = !$_SESSION['tasks'][$taskId]['completed'];
          }
        } elseif (isset($_POST['delete-task'])) {
          $taskId = intval($_POST['id']);
          if (isset($_SESSION['tasks'][$taskId])) {
            unset($_SESSION['tasks'][$taskId]);
            $_SESSION['tasks'] = array_values($_SESSION['tasks']);
          }
        }
      }

      $tasks = $_SESSION['tasks'];
      $completedTasksCount = count(array_filter($tasks, fn($task) => $task['completed']));
      $emptyItems = count($tasks) === 0;
      ?>

      <form method="post" action="" class="input-form">
        <input type="text" name="text" placeholder="Добавить новую задачу">
        <button type="submit" name="add-task">
          <span>Создать</span>
          <img src="/assets/img/add-icons.svg" alt="">
        </button>
      </form>

      <?php if ($errors): ?>
        <p class="error"><?= implode('<br>', $errors) ?></p>
      <?php endif; ?>

      <div class="items-info-task-and-done">
        <div class="task-all-info">
          Задачи созданы
          <span><?= count($tasks) ?></span>
        </div>
        <div class="task-all-info task-has-done <?= $completedTasksCount > 0 ? 'completed' : '' ?>">
          Завершенные
          <span><?= $completedTasksCount ?> из <?= count($tasks) ?></span>
        </div>
      </div>

      <?php if ($emptyItems): ?>
        <div class="empty-items">
          <img src="/assets/img/clipboard.svg" alt="">
          <p>
            <span>У вас еще нет зарегистрированных задач</span> <br>
            Создавайте задачи и организуйте свои дела
          </p>
        </div>
      <?php else: ?>
        <div class="items">
          <ul class="task">
            <?php foreach ($tasks as $index => $task): ?>
              <li class="item">
                <form action="" method="post" class="task-info">
                  <button class="text" type="submit" name="toggle-task-status" value="<?= $index ?>">
                    <input id="check<?= $index ?>" value="<?= $index ?>" class="check" type="checkbox" name="id" <?= $task['completed'] ? 'checked' : '' ?>>
                    <label for="check<?= $index ?>" id="custom-checkbox"></label>
                    <?= htmlspecialchars($task['text']) ?>
                  </button>
                </form>
                <form action="" method="post" class="deleted">
                  <button type="submit" name="delete-task">
                    <input type="hidden" value="<?= $index ?>" name="id">
                    <img src="/assets/img/trash.svg" alt="">
                  </button>
                </form>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>