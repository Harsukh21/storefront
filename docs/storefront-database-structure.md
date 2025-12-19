# Storefront Database Structure (No Relations)

> This wiki outlines every table needed for the storefront and admin flows. Fields reference other tables only by name; there are **no foreign keys** and no implicit model relationships.

## Identity & Authentication

### users
- id *(big serial, primary key)*
- name *(varchar 150)*
- email *(varchar 191, unique)*
- password *(varchar 255)*
- phone *(varchar 40, nullable)*
- avatar_path *(varchar 255, nullable)*
- email_verified_at *(timestamp, nullable)*
- remember_token *(varchar 100, nullable)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### admins
- id *(big serial, primary key)*
- name *(varchar 150)*
- email *(varchar 191, unique)*
- password *(varchar 255)*
- role *(varchar 50, default "manager")*
- last_login_at *(timestamp, nullable)*
- remember_token *(varchar 100, nullable)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### password_reset_tokens / admin_password_reset_tokens
- email *(varchar 191, indexed)*
- token *(varchar 255)*
- created_at *(timestamp, nullable)*

### user_addresses
- id *(big serial, primary key)*
- user_id *(bigint, indexed)*
- label *(varchar 100, nullable)*
- recipient_name *(varchar 150)*
- phone *(varchar 40, nullable)*
- line1 *(varchar 255)*
- line2 *(varchar 255, nullable)*
- city *(varchar 120)*
- state *(varchar 120, nullable)*
- postal_code *(varchar 30, nullable)*
- country *(varchar 2)*
- is_default_shipping *(boolean, default false)*
- is_default_billing *(boolean, default false)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

## Catalog

### categories
- id *(big serial, primary key)*
- parent_id *(bigint, indexed, nullable)*
- slug *(varchar 191, unique)*
- name *(varchar 150)*
- description *(text, nullable)*
- is_active *(boolean, default true)*
- meta_title *(varchar 191, nullable)*
- meta_description *(text, nullable)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### brands
- id *(big serial, primary key)*
- name *(varchar 150, unique)*
- slug *(varchar 191, unique)*
- description *(text, nullable)*
- logo_path *(varchar 255, nullable)*
- is_active *(boolean, default true)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### products
- id *(big serial, primary key)*
- category_id *(bigint, indexed)*
- brand_id *(bigint, indexed, nullable)*
- slug *(varchar 191, unique)*
- name *(varchar 191)*
- sku *(varchar 100, unique)*
- short_description *(text, nullable)*
- description *(text, nullable)*
- price *(numeric 12,2)*
- compare_at_price *(numeric 12,2, nullable)*
- tax_class *(varchar 50, nullable)*
- weight *(numeric 8,3, nullable)*
- width *(numeric 8,3, nullable)*
- height *(numeric 8,3, nullable)*
- depth *(numeric 8,3, nullable)*
- is_active *(boolean, default true)*
- is_featured *(boolean, default false)*
- meta_title *(varchar 191, nullable)*
- meta_description *(text, nullable)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### product_variants
- id *(big serial, primary key)*
- product_id *(bigint, indexed)*
- name *(varchar 191, nullable)*
- sku *(varchar 120, unique)*
- barcode *(varchar 120, nullable)*
- price *(numeric 12,2, nullable)*
- compare_at_price *(numeric 12,2, nullable)*
- weight *(numeric 8,3, nullable)*
- width *(numeric 8,3, nullable)*
- height *(numeric 8,3, nullable)*
- depth *(numeric 8,3, nullable)*
- is_active *(boolean, default true)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### product_option_types
- id *(big serial, primary key)*
- product_id *(bigint, indexed)*
- name *(varchar 100)*
- display_name *(varchar 120, nullable)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### product_option_values
- id *(big serial, primary key)*
- product_option_type_id *(bigint, indexed)*
- value *(varchar 120)*
- sort_order *(integer, default 0)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### variant_option_values
- id *(big serial, primary key)*
- product_variant_id *(bigint, indexed)*
- product_option_value_id *(bigint, indexed)*

### product_images
- id *(big serial, primary key)*
- product_id *(bigint, indexed)*
- file_path *(varchar 255)*
- alt_text *(varchar 191, nullable)*
- is_primary *(boolean, default false)*
- sort_order *(integer, default 0)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### product_attributes
- id *(big serial, primary key)*
- product_id *(bigint, indexed)*
- attribute_name *(varchar 120)*
- attribute_value *(varchar 255)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

## Inventory

### inventory_items
- id *(big serial, primary key)*
- product_id *(bigint, indexed, nullable)*
- product_variant_id *(bigint, indexed, nullable)*
- quantity_on_hand *(integer, default 0)*
- quantity_reserved *(integer, default 0)*
- quantity_available *(integer, default 0)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### inventory_adjustments
- id *(big serial, primary key)*
- inventory_item_id *(bigint, indexed)*
- admin_id *(bigint, indexed, nullable)*
- adjustment_type *(varchar 50)*
- quantity_change *(integer)*
- note *(text, nullable)*
- created_at *(timestamp)*

## Pricing & Promotions

### discounts
- id *(big serial, primary key)*
- code *(varchar 60, unique)*
- type *(varchar 30)*
- value *(numeric 12,2)*
- minimum_subtotal *(numeric 12,2, nullable)*
- usage_limit *(integer, nullable)*
- usage_limit_per_user *(integer, nullable)*
- starts_at *(timestamp, nullable)*
- expires_at *(timestamp, nullable)*
- is_active *(boolean, default true)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### discount_redemptions
- id *(big serial, primary key)*
- discount_id *(bigint, indexed)*
- user_id *(bigint, indexed, nullable)*
- order_id *(bigint, indexed)*
- redeemed_at *(timestamp)*

### tax_rates
- id *(big serial, primary key)*
- name *(varchar 120)*
- rate *(numeric 5,4)*
- country *(varchar 2, nullable)*
- state *(varchar 120, nullable)*
- postal_code *(varchar 20, nullable)*
- city *(varchar 120, nullable)*
- priority *(integer, default 0)*
- compound *(boolean, default false)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

## Shopping Session & Checkout

### carts
- id *(big serial, primary key)*
- user_id *(bigint, indexed, nullable)*
- session_id *(varchar 120, indexed, nullable)*
- currency *(varchar 3, default "USD")*
- subtotal *(numeric 12,2, default 0)*
- discount_total *(numeric 12,2, default 0)*
- tax_total *(numeric 12,2, default 0)*
- shipping_total *(numeric 12,2, default 0)*
- grand_total *(numeric 12,2, default 0)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### cart_items
- id *(big serial, primary key)*
- cart_id *(bigint, indexed)*
- product_id *(bigint, indexed, nullable)*
- product_variant_id *(bigint, indexed, nullable)*
- quantity *(integer)*
- unit_price *(numeric 12,2)*
- total_price *(numeric 12,2)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### orders
- id *(big serial, primary key)*
- order_number *(varchar 40, unique)*
- user_id *(bigint, indexed, nullable)*
- cart_id *(bigint, indexed, nullable)*
- status *(varchar 30)*
- payment_status *(varchar 30)*
- fulfillment_status *(varchar 30)*
- currency *(varchar 3, default "USD")*
- subtotal *(numeric 12,2)*
- discount_total *(numeric 12,2, default 0)*
- tax_total *(numeric 12,2, default 0)*
- shipping_total *(numeric 12,2, default 0)*
- grand_total *(numeric 12,2)*
- shipping_address_id *(bigint, indexed, nullable)*
- billing_address_id *(bigint, indexed, nullable)*
- discount_id *(bigint, indexed, nullable)*
- placed_at *(timestamp, nullable)*
- paid_at *(timestamp, nullable)*
- cancelled_at *(timestamp, nullable)*
- notes *(text, nullable)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### order_addresses
- id *(big serial, primary key)*
- order_id *(bigint, indexed)*
- type *(varchar 20)*
- recipient_name *(varchar 150)*
- phone *(varchar 40, nullable)*
- line1 *(varchar 255)*
- line2 *(varchar 255, nullable)*
- city *(varchar 120)*
- state *(varchar 120, nullable)*
- postal_code *(varchar 30, nullable)*
- country *(varchar 2)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### order_items
- id *(big serial, primary key)*
- order_id *(bigint, indexed)*
- product_id *(bigint, indexed, nullable)*
- product_variant_id *(bigint, indexed, nullable)*
- name_snapshot *(varchar 191)*
- sku_snapshot *(varchar 120, nullable)*
- unit_price *(numeric 12,2)*
- quantity *(integer)*
- tax_amount *(numeric 12,2, default 0)*
- discount_amount *(numeric 12,2, default 0)*
- total_price *(numeric 12,2)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### order_item_adjustments
- id *(big serial, primary key)*
- order_item_id *(bigint, indexed)*
- type *(varchar 50)*
- label *(varchar 120)*
- amount *(numeric 12,2)*
- created_at *(timestamp)*

### payments
- id *(big serial, primary key)*
- order_id *(bigint, indexed)*
- provider *(varchar 60)*
- transaction_id *(varchar 120, nullable)*
- amount *(numeric 12,2)*
- currency *(varchar 3, default "USD")*
- status *(varchar 30)*
- processed_at *(timestamp, nullable)*
- raw_response *(jsonb, nullable)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### payment_methods
- id *(big serial, primary key)*
- user_id *(bigint, indexed)*
- provider *(varchar 60)*
- provider_reference *(varchar 120)*
- brand *(varchar 60, nullable)*
- last4 *(varchar 4, nullable)*
- expires_on *(date, nullable)*
- is_default *(boolean, default false)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### refunds
- id *(big serial, primary key)*
- payment_id *(bigint, indexed)*
- amount *(numeric 12,2)*
- reason *(text, nullable)*
- status *(varchar 30)*
- processed_at *(timestamp, nullable)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### shipments
- id *(big serial, primary key)*
- order_id *(bigint, indexed)*
- shipment_number *(varchar 40, unique)*
- carrier *(varchar 60, nullable)*
- service *(varchar 60, nullable)*
- tracking_number *(varchar 120, nullable)*
- status *(varchar 30)*
- shipped_at *(timestamp, nullable)*
- delivered_at *(timestamp, nullable)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### shipment_items
- id *(big serial, primary key)*
- shipment_id *(bigint, indexed)*
- order_item_id *(bigint, indexed)*
- quantity *(integer)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

## Customer Experience

### wishlists
- id *(big serial, primary key)*
- user_id *(bigint, indexed)*
- name *(varchar 120, nullable)*
- is_default *(boolean, default true)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### wishlist_items
- id *(big serial, primary key)*
- wishlist_id *(bigint, indexed)*
- product_id *(bigint, indexed, nullable)*
- product_variant_id *(bigint, indexed, nullable)*
- created_at *(timestamp)*

### product_reviews
- id *(big serial, primary key)*
- product_id *(bigint, indexed)*
- user_id *(bigint, indexed)*
- rating *(smallint)*
- title *(varchar 191, nullable)*
- body *(text, nullable)*
- is_visible *(boolean, default true)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### review_helpfulness
- id *(big serial, primary key)*
- review_id *(bigint, indexed)*
- user_id *(bigint, indexed)*
- is_helpful *(boolean)*
- created_at *(timestamp)*

### product_questions
- id *(big serial, primary key)*
- product_id *(bigint, indexed)*
- user_id *(bigint, indexed, nullable)*
- question *(text)*
- is_visible *(boolean, default true)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

### product_answers
- id *(big serial, primary key)*
- question_id *(bigint, indexed)*
- admin_id *(bigint, indexed, nullable)*
- user_id *(bigint, indexed, nullable)*
- answer *(text)*
- is_visible *(boolean, default true)*
- created_at *(timestamp)*
- updated_at *(timestamp)*

## Admin Operations & Logs

### admin_activity_logs
- id *(big serial, primary key)*
- admin_id *(bigint, indexed, nullable)*
- action *(varchar 120)*
- auditable_type *(varchar 150, nullable)*
- auditable_id *(bigint, nullable)*
- metadata *(jsonb, nullable)*
- created_at *(timestamp)*

### admin_notifications
- id *(big serial, primary key)*
- admin_id *(bigint, indexed)*
- type *(varchar 150)*
- data *(jsonb)*
- read_at *(timestamp, nullable)*
- created_at *(timestamp)*

## System / Support Tables

- migrations *(id, migration, batch)*
- jobs *(id, queue, payload, attempts, reserved_at, available_at, created_at)*
- failed_jobs *(id, connection, queue, payload, exception, failed_at)*
- job_batches *(id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, created_at, cancelled_at, finished_at)*
- sessions *(id, user_id, ip_address, user_agent, payload, last_activity)*
- cache *(key, value, expiration)*
- cache_locks *(key, owner, expiration)*
- personal_access_tokens *(id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at)*

---

When you're ready, we can translate each section into migrations with the same column definitions—still without foreign keys or Eloquent relationships.
