CREATE TABLE m_application (
    api_key character varying(36) PRIMARY KEY,
    fqdn character varying(255) NOT NULL,
    user_account character varying(255) NOT NULL,
    dyno_used bigint DEFAULT -1 NOT NULL,
    dyno_quota bigint DEFAULT -1 NOT NULL,
    update_time timestamp DEFAULT localtimestamp NOT NULL,
    select_type integer NOT NULL
);

CREATE TABLE t_quota_history (
    history_seq bigserial PRIMARY KEY,
    update_time timestamp DEFAULT localtimestamp NOT NULL,
    quota_previous bigint NOT NULL,
    quota_current bigint NOT NULL,
    fqdn character varying(255) NOT NULL
);

CREATE FUNCTION set_update_time() RETURNS trigger
    LANGUAGE plpgsql
    AS $$begin
  NEW.update_time := localtimestamp;

  INSERT INTO t_quota_history (quota_previous, quota_current, fqdn)
  VALUES (OLD.dyno_used, NEW.dyno_used, OLD.fqdn);

  return NEW;
end;
$$;

CREATE TRIGGER update_trigger BEFORE UPDATE ON m_application FOR EACH ROW EXECUTE PROCEDURE set_update_time();
