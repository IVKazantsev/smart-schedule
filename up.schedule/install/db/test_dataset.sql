# Админстратор

INSERT INTO up_schedule_role (TITLE)
VALUES ('Администратор');

# Предметы


INSERT INTO up_schedule_subject(TITLE, AUDIENCE_TYPE_ID)
VALUES ('Теория вероятностей и математическая статистика Лекция', 1),
       ('Теория вероятностей и математическая статистика Практика', 2),
       ('Базы данных Лекция', 1),
       ('Базы данных Практика', 2),
       ('Уравнения математической физики Лекция', 1),
       ('Уравнения математической физики Практика', 2),
       ('Основы машинного обучения Лекция', 1),
       ('Основы машинного обучения Практика', 2),
       ('Численные методы Лекция', 1),
       ('Численные методы Практика', 2),
       ('Шаблоны разработки программного обеспечения Лекция', 1),
       ('Шаблоны разработки программного обеспечения Практика', 2),
       ('Основы разработки компьютерных игр Лекция', 1),
       ('Основы разработки компьютерных игр Практика', 2),
    # Матобы 4 курс
       ('Проектирование и администрирование информационных систем Лекция', 1),
       ('Проектирование и администрирование информационных систем Практика', 2),
       ('Основы информационной безопасности Лекция', 1),
       ('Основы информационной безопасности Практика', 2),
       ('Сравнительный анализ языков объектно-ориентированного программирования Лекция', 1),
       ('Сравнительный анализ языков объектно-ориентированного программирования Практика', 2),
       ('Тестирование и внедрение ПО Лекция', 1),
       ('Тестирование и внедрение ПО Практика', 2);
# Преподаватели

INSERT INTO b_user (LOGIN, PASSWORD, NAME, LAST_NAME, DATE_REGISTER)
VALUES ('teacher1',
        '$6$ww5jU7vqsGdIgylF$Lqp7dw5dRxGvTcOUqouUFWEAVRB.BvFJfaufgpQ11XdEHZydiY68728Qc.L3HRU.sUCtCuaMnEvLgcKARcbpl/',
        'Алексей',
        'Степанов',
        '2024-04-02 15:37:48'),
       ('teacher2',
        '$6$ww5jU7vqsGdIgylF$Lqp7dw5dRxGvTcOUqouUFWEAVRB.BvFJfaufgpQ11XdEHZydiY68728Qc.L3HRU.sUCtCuaMnEvLgcKARcbpl/',
        'Дмитрий',
        'Савкин',
        '2024-04-02 15:37:48'),
       ('teacher3',
        '$6$ww5jU7vqsGdIgylF$Lqp7dw5dRxGvTcOUqouUFWEAVRB.BvFJfaufgpQ11XdEHZydiY68728Qc.L3HRU.sUCtCuaMnEvLgcKARcbpl/',
        'Екатерина',
        'Васильева',
        '2024-04-02 15:37:48'),
       ('teacher4',
        '$6$ww5jU7vqsGdIgylF$Lqp7dw5dRxGvTcOUqouUFWEAVRB.BvFJfaufgpQ11XdEHZydiY68728Qc.L3HRU.sUCtCuaMnEvLgcKARcbpl/',
        'Богдан',
        'Мищук',
        '2024-04-02 15:37:48'),
       ('teacher5',
        '$6$ww5jU7vqsGdIgylF$Lqp7dw5dRxGvTcOUqouUFWEAVRB.BvFJfaufgpQ11XdEHZydiY68728Qc.L3HRU.sUCtCuaMnEvLgcKARcbpl/',
        'Леонид',
        'Зинин',
        '2024-04-02 15:37:48'),
       ('teacher6',
        '$6$ww5jU7vqsGdIgylF$Lqp7dw5dRxGvTcOUqouUFWEAVRB.BvFJfaufgpQ11XdEHZydiY68728Qc.L3HRU.sUCtCuaMnEvLgcKARcbpl/',
        'Дмитрий',
        'Чемакин',
        '2024-04-02 15:37:48'),
       ('teacher7',
        '$6$ww5jU7vqsGdIgylF$Lqp7dw5dRxGvTcOUqouUFWEAVRB.BvFJfaufgpQ11XdEHZydiY68728Qc.L3HRU.sUCtCuaMnEvLgcKARcbpl/',
        'Вадим',
        'Старыгин',
        '2024-04-02 15:37:48'),
       ('teacher8',
        '$6$ww5jU7vqsGdIgylF$Lqp7dw5dRxGvTcOUqouUFWEAVRB.BvFJfaufgpQ11XdEHZydiY68728Qc.L3HRU.sUCtCuaMnEvLgcKARcbpl/',
        'Игорь',
        'Ветров',
        '2024-04-02 15:37:48'),
       ('teacher9',
        '$6$ww5jU7vqsGdIgylF$Lqp7dw5dRxGvTcOUqouUFWEAVRB.BvFJfaufgpQ11XdEHZydiY68728Qc.L3HRU.sUCtCuaMnEvLgcKARcbpl/',
        'Герман',
        'Копытов',
        '2024-04-02 15:37:48');

INSERT INTO up_schedule_role (TITLE)
VALUES ('Преподаватель');

INSERT INTO b_uts_user (VALUE_ID, UF_ROLE_ID)
VALUES
        (1,1),
        (2,2),
        (3,2),
        (4,2),
        (5,2),
        (6,2),
        (7,2),
        (8,2),
        (9,2),
        (10,2);

INSERT INTO up_schedule_subject_teacher(TEACHER_ID, SUBJECT_ID)
VALUES (2, 1),
       (2, 2),
       (3, 3),
       (3, 4),
       (4, 5),
       (4, 6),
       (5, 7),
       (5, 8),
       (6, 9),
       (6, 10),
       (3, 11),
       (7, 12),
       (8, 13),
       (8, 14),
       (5, 15),
       (5, 16),
       (9, 17),
       (9, 18),
       (10, 19),
       (10, 20),
       (7, 21),
       (7, 22);

# Группы

INSERT INTO up_schedule_group (TITLE)
VALUES ('Прикладная математика, 3 курс, 2 группа'),
       ('Математическое обеспечение 4 курс');

INSERT INTO up_schedule_group_subject (SUBJECT_ID, GROUP_ID, HOURS_NUMBER)
VALUES (1,1,1),
       (2,1,1),
       (3,1,1),
       (4,1,1),
       (5,1,1),
       (6,1,1),
       (7,1,1),
       (8,1,1),
       (9,1,1),
       (10,1,1),
       (11,1,1),
       (12,1,1),
       (13,1,1),
       (14,1,1),
       (15,2,1),
       (16,2,1),
       (17,2,1),
       (18,2,1),
       (19,2,1),
       (20,2,1),
       (21,2,1),
       (22,2,1);

# Студенты

INSERT INTO up_schedule_role (TITLE)
VALUES ('Студент');

INSERT INTO b_user (LOGIN, PASSWORD, NAME, LAST_NAME, DATE_REGISTER)
VALUES ('student1',
        '$6$ww5jU7vqsGdIgylF$Lqp7dw5dRxGvTcOUqouUFWEAVRB.BvFJfaufgpQ11XdEHZydiY68728Qc.L3HRU.sUCtCuaMnEvLgcKARcbpl/',
        'Илья',
        'Казанцев',
        '2024-04-02 15:37:48'),
       ('student2',
        '$6$ww5jU7vqsGdIgylF$Lqp7dw5dRxGvTcOUqouUFWEAVRB.BvFJfaufgpQ11XdEHZydiY68728Qc.L3HRU.sUCtCuaMnEvLgcKARcbpl/',
        'Артем',
        'Калиниченко',
        '2024-04-02 15:37:48'),
       ('student3',
        '$6$ww5jU7vqsGdIgylF$Lqp7dw5dRxGvTcOUqouUFWEAVRB.BvFJfaufgpQ11XdEHZydiY68728Qc.L3HRU.sUCtCuaMnEvLgcKARcbpl/',
        'Софья',
        'Широкая',
        '2024-04-02 15:37:48'),
       ('student4',
        '$6$ww5jU7vqsGdIgylF$Lqp7dw5dRxGvTcOUqouUFWEAVRB.BvFJfaufgpQ11XdEHZydiY68728Qc.L3HRU.sUCtCuaMnEvLgcKARcbpl/',
        'Алексей',
        'Харымов',
        '2024-04-02 15:37:48');

INSERT INTO b_uts_user (VALUE_ID, UF_ROLE_ID, UF_GROUP_ID)
VALUES (11,3, 1),
       (12,3, 1),
       (13,3, 1),
       (14,3, 2);

# Аудитории

INSERT INTO up_schedule_audience_type (TITLE)
VALUES ('Лекционная'),
       ('Практическая'),
       ('Онлайн');

INSERT INTO up_schedule_audience (NUMBER, AUDIENCE_TYPE_ID)
VALUES ('онлайн', 3),
       (205, 2),
       (209, 2),
       (214, 2),
       (230, 2),
       (213, 2),
       (233, 1),
       (231, 1),
    # Матобы
       (220, 1),
       (221, 2),
       (225, 1),
       (226, 2),
       (222, 1),
       (223, 2),
       (227, 1),
       (228, 2);


# Пары

INSERT INTO up_schedule_couple (GROUP_ID, SUBJECT_ID, TEACHER_ID, AUDIENCE_ID, WEEK_DAY, COUPLE_NUMBER_IN_DAY)
VALUES (1, 1, 2, 1, 1, 1),
       (1, 2, 2, 2, 1, 3),
       (1, 3, 3, 8, 5, 3),
       (1, 4, 3, 4, 1, 5),
       (1, 5, 4, 8, 5, 2),
       (1, 6, 4, 3, 1, 4),
       (1, 7, 5, 1, 2, 1),
       (1, 8, 5, 1, 2, 3),
       (1, 9, 6, 7, 4, 4),
       (1, 10, 6, 5, 4, 1),
       (1, 11, 3, 7, 4, 3),
       (1, 12, 7, 6, 4, 2),
       (1, 13, 8, 1, 6, 1),
       (1, 14, 8, 1, 6, 2),
       # Матобы
       (2, 15, 5, 9, 1, 3),
       (2, 16, 5, 10, 1, 4),
       (2, 17, 9, 11, 2, 2),
       (2, 18, 9, 12, 2, 3),
       (2, 19, 10, 13, 3, 1),
       (2, 20, 10, 14, 3, 2),
       (2, 21, 7, 15, 6, 4),
       (2, 22, 7, 16, 6, 5);
