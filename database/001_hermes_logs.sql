create table if not exists hermes_logs (
  id bigserial primary key,
  created_at timestamptz not null default now(),
  level text not null default 'INFO',
  client_id text,
  workflow_name text,
  agent text,
  source text,
  content_id text,
  action text,
  model text,
  tokens integer,
  estimated_cost numeric(12, 6),
  duration_ms integer,
  score integer,
  approval_status text,
  error_message text,
  metadata jsonb not null default '{}'::jsonb
);

create index if not exists hermes_logs_created_at_idx on hermes_logs (created_at desc);
create index if not exists hermes_logs_client_idx on hermes_logs (client_id);
create index if not exists hermes_logs_level_idx on hermes_logs (level);

