-- SETTINGS
UPDATE `sys_options` SET `caption`='Log additional debug info with each long sql query' WHERE `name`='bx_profiler_long_sql_queries_debug';
UPDATE `sys_options` SET `caption`='Log additional debug info with each long module query' WHERE `name`='bx_profiler_long_module_query_debug';
UPDATE `sys_options` SET `caption`='Log additional debug info with each long page open' WHERE `name`='bx_profiler_long_page_debug';
