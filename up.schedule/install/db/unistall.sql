DROP TABLE IF EXISTS up_schedule_subject;
DROP TABLE IF EXISTS up_schedule_audience;
DROP TABLE IF EXISTS up_schedule_audience_type;
DROP TABLE IF EXISTS up_schedule_group;
DROP TABLE IF EXISTS up_schedule_group_subject;
DROP TABLE IF EXISTS up_schedule_subject_teacher;
DROP TABLE IF EXISTS up_schedule_role;
DROP TABLE IF EXISTS up_schedule_couple;

DELETE FROM b_user
WHERE LOGIN IN ('teacher1',
                'teacher2',
                'teacher3',
                'teacher4',
                'teacher5',
                'teacher6',
                'teacher7',
                'teacher8',
                'teacher9',
                'student1',
                'student2',
                'student3',
                'student4');

DELETE FROM b_uts_user
WHERE UF_GROUP_ID IS NOT NULL OR UF_ROLE_ID IS NOT NULL;
