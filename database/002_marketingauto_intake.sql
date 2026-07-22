create table if not exists marketingauto_sources (
  id bigserial primary key,
  created_at timestamptz not null default now(),
  updated_at timestamptz not null default now(),
  client_id text not null,
  input_url text not null,
  normalized_url text,
  platform text not null default 'other',
  source_type text not null default 'site',
  access_mode text not null default 'manual_review',
  territory text,
  keywords text[] not null default '{}',
  compliance_notes jsonb not null default '[]'::jsonb,
  status text not null default 'pending',
  metadata jsonb not null default '{}'::jsonb
);

create index if not exists marketingauto_sources_client_idx on marketingauto_sources (client_id);
create index if not exists marketingauto_sources_platform_idx on marketingauto_sources (platform);
create index if not exists marketingauto_sources_status_idx on marketingauto_sources (status);
create unique index if not exists marketingauto_sources_client_url_idx
  on marketingauto_sources (client_id, input_url);

create table if not exists marketingauto_autopost_forms (
  id bigserial primary key,
  created_at timestamptz not null default now(),
  updated_at timestamptz not null default now(),
  client_id text not null,
  source_id bigint references marketingauto_sources(id),
  platform text not null default 'other',
  source_url text,
  content_id text,
  score integer not null default 0,
  guardian_status text not null default 'VALIDATION_HUMAINE_OBLIGATOIRE',
  fact_check_status text not null default 'needs_review',
  draft_message text,
  edited_message text,
  approval_decision text,
  approver text,
  approved_at timestamptz,
  next_action text not null default 'none',
  metadata jsonb not null default '{}'::jsonb
);

create index if not exists marketingauto_autopost_forms_client_idx on marketingauto_autopost_forms (client_id);
create index if not exists marketingauto_autopost_forms_platform_idx on marketingauto_autopost_forms (platform);
create index if not exists marketingauto_autopost_forms_decision_idx on marketingauto_autopost_forms (approval_decision);

