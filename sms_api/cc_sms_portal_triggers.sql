/*
Navicat MySQL Data Transfer

Source Server         : 192.168.10.64
Source Server Version : 50551
Source Host           : 192.168.10.64:3306
Source Database       : cc_sms_portal

Target Server Type    : MYSQL
Target Server Version : 50551
File Encoding         : 65001

Date: 2019-06-12 11:17:43
*/

DROP TRIGGER IF EXISTS sms_schedule_delete;
DELIMITER $$
CREATE TRIGGER sms_schedule_delete AFTER DELETE ON sms_schedule 
FOR EACH ROW 
BEGIN
   IF OLD.num_contacts > 0 AND OLD.num_sms_sent > 0 THEN
      INSERT INTO log_schedule SET id = OLD.id, account_id = OLD.account_id, sms_from = OLD.sms_from, sms_text = OLD.sms_text, time_zone = OLD.time_zone,
            start_time = OLD.start_time, stop_time = NOW(), is_repeat = OLD.is_repeat,
            num_contacts = OLD.num_contacts, num_sms_sent = OLD.num_sms_sent, status = OLD.status, created_by = OLD.created_by,
            updated_by = OLD.updated_by, created_at = OLD.created_at, updated_at = OLD.updated_at;
   END IF;
END
$$
DELIMITER ;

DROP TRIGGER IF EXISTS sms_schedule_contact_delete;
DELIMITER $$
CREATE TRIGGER sms_schedule_contact_delete AFTER DELETE ON sms_schedule_contact 
FOR EACH ROW 
BEGIN
    INSERT INTO log_schedule_contact SET id = OLD.id, account_id = OLD.account_id, schedule_id = OLD.schedule_id, phone = OLD.phone,
        group_id = OLD.group_id, status = OLD.status, created_by = OLD.created_by, updated_by = OLD.updated_by,
        created_at = OLD.created_at, updated_at = OLD.updated_at;
END
$$
DELIMITER ;
