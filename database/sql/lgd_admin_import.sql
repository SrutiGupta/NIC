-- LGD Administrative Data Import (PostgreSQL psql script)
-- Run from terminal after connecting with:
-- psql -U postgres -d nic_db -h 127.0.0.1 -p 5432 -f database/sql/lgd_admin_import.sql

BEGIN;

TRUNCATE TABLE lgd_blocks, lgd_subdistricts, lgd_districts, lgd_states RESTART IDENTITY CASCADE;

\copy lgd_states(serial_no,state_code,state_version,state_name,state_name_repeat,census_2001_code,census_2011_code,state_or_ut) FROM 'C:/Sruti/NIC244/india-local-government-directory-main/administrative/1-state.csv' DELIMITER ',' CSV HEADER;

\copy lgd_districts(state_code,state_name,district_code,district_name,census_2001_code,census_2011_code) FROM 'C:/Sruti/NIC244/india-local-government-directory-main/administrative/2-district.csv' DELIMITER ',' CSV HEADER;

\copy lgd_subdistricts(serial_no,state_code,state_name,district_code,district_name,subdistrict_code,subdistrict_version,subdistrict_name,census_2001_code,census_2011_code) FROM 'C:/Sruti/NIC244/india-local-government-directory-main/administrative/3-subdistrict.csv' DELIMITER ',' CSV HEADER;

\copy lgd_blocks(serial_no,state_code,state_name,district_code,district_name,block_code,block_version,block_name,block_name_repeat) FROM 'C:/Sruti/NIC244/india-local-government-directory-main/administrative/blocks.csv' DELIMITER ',' CSV HEADER;

COMMIT;
