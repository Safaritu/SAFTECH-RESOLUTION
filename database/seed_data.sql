USE saftech_resolution;

-- Services
INSERT INTO services (title, description, icon, color_theme, sort_order) VALUES
('Web Applications', 'Custom enterprise portals, dashboards, business platforms.', 'fa-laptop-code', 'sky', 1),
('Mobile Solutions', 'Cross-platform apps for iOS & Android.', 'fa-mobile-alt', 'purple', 2),
('Cloud & DevOps', 'CI/CD pipelines, containerization, cloud infrastructure.', 'fa-cloud', 'orange', 3);

-- Live Demos
INSERT INTO projects (title, category, icon, tags, color_theme, is_live, sort_order, description, image_path) VALUES
('Auto Spare Management System', 'live_demo', NULL, NULL, 'sky', 1, 1, 'Auto spares inventory, supplier management, sales tracking, POS system.', 'https://images.unsplash.com/photo-1723365316514-8509dea457f2?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0'),
('Pharmacy Management', 'live_demo', NULL, NULL, 'sky', 1, 2, 'Expiry tracking, supplier orders, profit analytics, medicine inventory.', 'https://plus.unsplash.com/premium_photo-1661769786626-8025c37907ae?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0'),
('Clinic Management', 'live_demo', NULL, NULL, 'purple', 1, 3, 'Patient records, appointments, drug health monitoring, expiry alerts.', 'https://images.unsplash.com/photo-1589279003513-467d320f47eb?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0'),
('Boutique Management', 'live_demo', NULL, NULL, 'pink', 1, 4, 'VIP loyalty, size tracking, layaway, returns management.', 'https://plus.unsplash.com/premium_photo-1701102865211-c18f4de7ef06?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0'),
('StockMaster', 'live_demo', NULL, NULL, 'emerald', 1, 5, 'Universal inventory management, multi-warehouse support.', 'https://images.unsplash.com/photo-1757837593538-b4a8654132f1?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0'),
('Electrical POS System', 'live_demo', NULL, NULL, 'orange', 1, 6, 'Warranty tracking, repair services, installation scheduling.', 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'),
('House Tenant Management', 'live_demo', NULL, NULL, 'teal', 1, 7, 'Rent collection, maintenance tracking, lease management, multiple apartments.', 'https://images.unsplash.com/photo-1567496898669-ee935f5f647a?q=80&w=1171&auto=format&fit=crop&ixlib=rb-4.1.0');

-- Enterprise Solutions
INSERT INTO projects (title, category, category_label, icon, tags, is_live, sort_order, description, image_path) VALUES
('LendingOps Pro', 'enterprise', 'FINANCE', 'fa-coins', 'Loans,Interest,Repayments', 0, 1, 'Micro-loans management, interest calculations, repayment schedules with real-time tracking.', 'https://images.unsplash.com/photo-1563013544-824ae1b704d9?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'),
('Streamline ERP', 'enterprise', 'ERP', 'fa-industry', 'Materials,Batch,Inventory', 0, 2, 'Raw materials, production batches, finished goods management with real-time analytics.', 'https://images.unsplash.com/photo-1581091226033-d5c48150dbaa?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'),
('RouteMaster', 'enterprise', 'LOGISTICS', 'fa-route', 'Sales,Drivers,Fulfillment', 0, 3, 'Field sales tracking, driver management, retail outlet fulfillment portal.', 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'),
('Workforce 360', 'enterprise', 'HR', 'fa-users', 'Payroll,Attendance,Multi-dept', 0, 4, 'Centralized HR and payroll system for multi-department organizations.', 'https://images.unsplash.com/photo-1553877522-43269d4ea984?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'),
('BatchFlow QC', 'enterprise', 'QUALITY', 'fa-clipboard-list', 'Testing,Batch,Compliance', 0, 5, 'Digital logging for strict quality testing and batch compliance.', 'https://images.unsplash.com/photo-1581093588401-fbb62a02f120?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'),
('AquaFlow Telemetry', 'enterprise', 'IOT', 'fa-microchip', 'IoT,Telemetry,Efficiency', 0, 6, 'Liquid storage monitoring and pump station efficiency tracking.', 'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'),
('Solar WaaS', 'enterprise', 'SOLAR', 'fa-solar-panel', 'Warranty,Service,Solar', 0, 7, 'Warranty-as-a-Service for solar and electrical contractors.', 'https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'),
('Rent-Logic', 'enterprise', 'PROPERTY', 'fa-building', 'Tenants,Rent,Maintenance', 0, 8, 'Tenant tracking, rent collection, maintenance scheduling, financial reports.', 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'),
('Med-Bill Pro', 'enterprise', 'HEALTHCARE', 'fa-hospital-user', 'Billing,Insurance,Claims', 0, 9, 'Pharmacy & clinic billing SaaS with insurance claims, patient billing.', 'https://images.unsplash.com/photo-1576671081837-49000212a370?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'),
('Garage Autospares', 'enterprise', 'AUTOMOTIVE', 'fa-car', 'Inventory,POS,Suppliers', 0, 10, 'Auto spares inventory, supplier management, sales tracking, POS system.', 'https://images.unsplash.com/photo-1723365316514-8509dea457f2?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0'),
('Agro-SaaS', 'enterprise', 'AGRICULTURE', 'fa-tractor', 'Crops,Livestock,Harvest', 0, 11, 'Crop tracking, livestock management, harvest planning, sales analytics.', 'https://images.unsplash.com/photo-1464226184884-fa280b87c399?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'),
('EduTrack Pro', 'enterprise', 'EDUCATION', 'fa-graduation-cap', 'Students,Fees,Exams', 0, 12, 'Student records, fee collection, exam management, parent portal.', 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'),
('RestoPOS', 'enterprise', 'HOSPITALITY', 'fa-utensils', 'Orders,Tables,Kitchen', 0, 13, 'Order management, table booking, inventory, kitchen display system.', 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80');

-- Portfolio (Featured Projects section)
INSERT INTO projects (title, category, color_theme, is_featured, sort_order, description, image_path) VALUES
('Pharmacy System', 'portfolio', 'sky', 1, 1, 'Expiry tracking, supplier orders, medicine inventory.', 'https://images.unsplash.com/photo-1576671081837-49000212a370?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'),
('Electrical POS', 'portfolio', 'orange', 1, 2, 'Warranty tracking, repairs, installation scheduling.', 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'),
('Tenant Management', 'portfolio', 'teal', 1, 3, 'Rent collection, maintenance, apartment management.', 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80');
