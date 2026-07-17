-- =========================================================
-- Instructor Role Permission Setup for Hamro Learning LMS
-- Role ID 2 = Instructor
-- =========================================================

-- Step 1: Get the MAX current id so we don't conflict
-- Step 2: Insert missing permissions for instructor role

SET @role_id = 2;

-- Remove existing instructor permissions so we start fresh
DELETE FROM `role_permission` WHERE `role_id` = @role_id;

-- Re-insert all correct permissions for Instructor (role_id = 2)
INSERT INTO `role_permission` (`permission_id`, `role_id`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES

-- Dashboard (1) + all dashboard widgets (273-285)
(1,  @role_id, 1, 1, 1, NOW(), NOW()),
(273,@role_id, 1, 1, 1, NOW(), NOW()),
(274,@role_id, 1, 1, 1, NOW(), NOW()),
(275,@role_id, 1, 1, 1, NOW(), NOW()),
(276,@role_id, 1, 1, 1, NOW(), NOW()),
(277,@role_id, 1, 1, 1, NOW(), NOW()),
(278,@role_id, 1, 1, 1, NOW(), NOW()),
(279,@role_id, 1, 1, 1, NOW(), NOW()),
(280,@role_id, 1, 1, 1, NOW(), NOW()),
(281,@role_id, 1, 1, 1, NOW(), NOW()),
(282,@role_id, 1, 1, 1, NOW(), NOW()),
(283,@role_id, 1, 1, 1, NOW(), NOW()),
(284,@role_id, 1, 1, 1, NOW(), NOW()),
(285,@role_id, 1, 1, 1, NOW(), NOW()),

-- Instructors menu (4) + Payout List (49)
(4,  @role_id, 1, 1, 1, NOW(), NOW()),
(49, @role_id, 1, 1, 1, NOW(), NOW()),

-- Courses menu (5) + All Courses (60) + Add (61) + Edit (62) + Details (63) + Change Status (67)
(5,  @role_id, 1, 1, 1, NOW(), NOW()),
(60, @role_id, 1, 1, 1, NOW(), NOW()),
(61, @role_id, 1, 1, 1, NOW(), NOW()),
(62, @role_id, 1, 1, 1, NOW(), NOW()),
(63, @role_id, 1, 1, 1, NOW(), NOW()),
(64, @role_id, 1, 1, 1, NOW(), NOW()),
(67, @role_id, 1, 1, 1, NOW(), NOW()),

-- Active + Pending courses
(72, @role_id, 1, 1, 1, NOW(), NOW()),
(74, @role_id, 1, 1, 1, NOW(), NOW()),

-- Chapter (68) + Add (69) + Edit (70) + Delete (71)
(68, @role_id, 1, 1, 1, NOW(), NOW()),
(69, @role_id, 1, 1, 1, NOW(), NOW()),
(70, @role_id, 1, 1, 1, NOW(), NOW()),
(71, @role_id, 1, 1, 1, NOW(), NOW()),

-- Quiz (7) + Question Group (105) + Add (106) + Edit (107) + Delete (108)
(7,  @role_id, 1, 1, 1, NOW(), NOW()),
(105,@role_id, 1, 1, 1, NOW(), NOW()),
(106,@role_id, 1, 1, 1, NOW(), NOW()),
(107,@role_id, 1, 1, 1, NOW(), NOW()),
(108,@role_id, 1, 1, 1, NOW(), NOW()),

-- Question Bank (109) + Add (110) + Edit (111) + Delete (112)
(109,@role_id, 1, 1, 1, NOW(), NOW()),
(110,@role_id, 1, 1, 1, NOW(), NOW()),
(111,@role_id, 1, 1, 1, NOW(), NOW()),
(112,@role_id, 1, 1, 1, NOW(), NOW()),

-- Set Quiz (113) + Add (114) + Edit (115) + Delete (116)
(113,@role_id, 1, 1, 1, NOW(), NOW()),
(114,@role_id, 1, 1, 1, NOW(), NOW()),
(115,@role_id, 1, 1, 1, NOW(), NOW()),
(116,@role_id, 1, 1, 1, NOW(), NOW()),
(117,@role_id, 1, 1, 1, NOW(), NOW()),
(118,@role_id, 1, 1, 1, NOW(), NOW()),
(119,@role_id, 1, 1, 1, NOW(), NOW()),
(120,@role_id, 1, 1, 1, NOW(), NOW()),
(216,@role_id, 1, 1, 1, NOW(), NOW()),

-- Quiz Setup (121)
(121,@role_id, 1, 1, 1, NOW(), NOW()),
(122,@role_id, 1, 1, 1, NOW(), NOW()),

-- Quiz Report (123)
(123,@role_id, 1, 1, 1, NOW(), NOW()),

-- Communications (8) + Private Message (124) + Send (125)
(8,  @role_id, 1, 1, 1, NOW(), NOW()),
(124,@role_id, 1, 1, 1, NOW(), NOW()),
(125,@role_id, 1, 1, 1, NOW(), NOW()),

-- Payments (9)
(9,  @role_id, 1, 1, 1, NOW(), NOW()),

-- Reports (18) + Admin Revenue (134) + Instructor Revenue (135)
(18, @role_id, 1, 1, 1, NOW(), NOW()),
(134,@role_id, 1, 1, 1, NOW(), NOW()),
(135,@role_id, 1, 1, 1, NOW(), NOW());

SELECT CONCAT('Done! Instructor role now has ', COUNT(*), ' permissions.') AS result
FROM `role_permission`
WHERE `role_id` = 2;
