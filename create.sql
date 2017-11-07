CREATE TABLE m_application (
    api_key character varying(36) PRIMARY KEY,
    fqdn character varying(255) NOT NULL,
    user_account character varying(255) NOT NULL,
    dyno_used bigint DEFAULT -1 NOT NULL,
    dyno_quota bigint DEFAULT -1 NOT NULL,
    update_time timestamp DEFAULT localtimestamp NOT NULL,
    select_type integer NOT NULL
);

CREATE FUNCTION set_update_time() RETURNS trigger
    LANGUAGE plpgsql
    AS $$begin
  NEW.update_time := localtimestamp;

  return NEW;
end;
$$;

CREATE TRIGGER update_trigger BEFORE UPDATE ON m_application FOR EACH ROW EXECUTE PROCEDURE set_update_time();

CREATE VIEW v_rest AS 
SELECT M1.*
      ,((M1.dyno_quota - M1.dyno_used) / 86400) d
      ,(((M1.dyno_quota - M1.dyno_used) / 3600) % (24)::bigint) h
      ,(((M1.dyno_quota - M1.dyno_used) / 60) % (60)::bigint) m
      ,date_trunc('month', date 'today' + interval '1 month') - date 'today' rest
  FROM m_application M1
 ORDER BY M1.dyno_used DESC;
