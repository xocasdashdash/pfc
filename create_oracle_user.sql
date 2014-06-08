drop tablespace tbs_uah_gat_01;
CREATE TABLESPACE tbs_uah_gat_01 DATAFILE 'tbs_uah_gat_f2.dbf' SIZE 40M REUSE AUTOEXTEND ON NEXT 40M MAXSIZE 200M  ONLINE ;
drop user uah_gat;
create user uah_gat identified by jfc$24uah default tablespace tbs_uah_gat_01 QUOTA UNLIMITED on tbs_uah_gat_01;
DROP ROLE UAH_GAT_ROLE;
create ROLE UAH_GAT_ROLE;
GRANT CREATE session, CREATE table, CREATE view, 
      CREATE procedure,CREATE synonym/*,*
      ALTER table, ALTER view, ALTER procedure,ALTER synonym,
      DROP table, DROP view, DROP procedure,DROP synonym*/
      TO UAH_GAT_ROLE;
GRANT UAH_GAT_ROLE TO UAH_GAT;
GRANT CONNECT, RESOURCE TO UAH_GAT;