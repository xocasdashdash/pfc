ALTER TABLE UAH_GAT_Activity DROP FOREIGN KEY UAH_GAT_FK_FA8719131AEAE82;
ALTER TABLE UAH_GAT_Activity DROP FOREIGN KEY UAH_GAT_FK_FA8719199D47173;
ALTER TABLE UAH_GAT_Activity_Degree DROP FOREIGN KEY UAH_GAT_FK_C8C9E83BB35C5756;
ALTER TABLE UAH_GAT_Activity_Degree DROP FOREIGN KEY UAH_GAT_FK_C8C9E83B81C06096;
ALTER TABLE UAH_GAT_Application DROP FOREIGN KEY UAH_GAT_FK_5122C9C2A76ED395;
ALTER TABLE UAH_GAT_DefaultPermits_Roles DROP FOREIGN KEY UAH_GAT_FK_F859B983D60322AC;
ALTER TABLE UAH_GAT_DefaultPermits_Roles DROP FOREIGN KEY UAH_GAT_FK_F859B9838C23CD63;
ALTER TABLE UAH_GAT_Degree DROP FOREIGN KEY UAH_GAT_FK_6F92DAFC1AE9C52E;
ALTER TABLE UAH_GAT_Enrollment DROP FOREIGN KEY UAH_GAT_FK_AD0C83B63E030ACD;
ALTER TABLE UAH_GAT_Enrollment DROP FOREIGN KEY UAH_GAT_FK_AD0C83B67B00651C;
ALTER TABLE UAH_GAT_Enrollment DROP FOREIGN KEY UAH_GAT_FK_AD0C83B681C06096;
ALTER TABLE UAH_GAT_Enrollment DROP FOREIGN KEY UAH_GAT_FK_AD0C83B6A76ED395;
ALTER TABLE UAH_GAT_User DROP FOREIGN KEY UAH_GAT_FK_8D0E506B35C5756;
ALTER TABLE UAH_GAT_User_Roles DROP FOREIGN KEY UAH_GAT_FK_E3EDAECCD60322AC;
ALTER TABLE UAH_GAT_User_Roles DROP FOREIGN KEY UAH_GAT_FK_E3EDAECCA76ED395;
ALTER TABLE UAH_GAT_openid_identities DROP FOREIGN KEY UAH_GAT_FK_5DB9C238A76ED395;
DROP TABLE UAH_GAT_Activity CASCADE CONSTRAINTS;
DROP TABLE UAH_GAT_Activity_Degree CASCADE CONSTRAINTS;
DROP TABLE UAH_GAT_Application CASCADE CONSTRAINTS;
DROP TABLE UAH_GAT_DefaultPermits CASCADE CONSTRAINTS;
DROP TABLE UAH_GAT_DefaultPermits_Roles CASCADE CONSTRAINTS;
DROP TABLE UAH_GAT_Degree CASCADE CONSTRAINTS;
DROP TABLE UAH_GAT_Enrollment CASCADE CONSTRAINTS;
DROP TABLE UAH_GAT_Role CASCADE CONSTRAINTS;
DROP TABLE UAH_GAT_Session CASCADE CONSTRAINTS;
DROP TABLE UAH_GAT_Status CASCADE CONSTRAINTS;
DROP TABLE UAH_GAT_User CASCADE CONSTRAINTS;
DROP TABLE UAH_GAT_User_Roles CASCADE CONSTRAINTS;
DROP TABLE UAH_GAT_openid_identities CASCADE CONSTRAINTS;