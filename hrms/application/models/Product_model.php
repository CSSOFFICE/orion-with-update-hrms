<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends CI_Model
{
    public function get_products()
    {
        return $this->db->get('product');
    }
    public function add_product($data)
    {
        $this->db->insert('product', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    public function read_product($id)
    {
        $q = $this->db->where('product_id', $id)->get('product');
        return $q->result();
    }
    public function update($data, $id)
    {
        $this->db->where('product_id', $id);
        if ($this->db->update('product', $data)) {
            return true;
        } else {
            return false;
        }
    }
    public function update_product_barcode($product_id, $barcode_path)
    {
        $this->db->where('product_id', $product_id);
        return $this->db->update('product', array('barcode' => $barcode_path));
    }

    public function delete_record($id)
    {
        // Get the product details to retrieve the image and QR code paths
        // $product = $this->db->where('product_id', $id)->get('product')->row();

        // if ($product) {
        // Delete the product image
        // $image_path = base_url().'/uploads/product/' . $product->prd_img;
        // if (file_exists($image_path)) {
        //     unlink($image_path);
        // }

        // // Delete the product QR code
        // $qr_code_path = $product->barcode;
        // if (file_exists($qr_code_path)) {
        //     unlink($qr_code_path);
        // }

        // Delete the product record from the database
        $this->db->where('product_id', $id);
        $this->db->delete('product');
        return true;
        // } else {
        //     return false;
        // }
    }
    public function get_supplier_name()
    {
        $q = $this->db->get('xin_suppliers');
        return $q->result();
    }

    ////////// WAREHOUSE ///////// 

    public function get_company()
    {
        $q = $this->db->get('xin_companies');
        if ($q) {
            return $q->result_array();
        }
    }

    public function add_warehouse($data)
    {
        $this->db->insert('warehouse', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function get_warehouse()
    {
        return $this->db->select('warehouse.*,xin_companies.name as organization,xin_companies.company_id')
            ->from('warehouse')
            ->join('xin_companies', 'warehouse.org_id=xin_companies.company_id')
            ->get();
    }

    public function read_warehouse($id)
    {
        $q = $this->db->where('w_id', $id)->get('warehouse');
        return $q->result();
    }

    public function update_warehouse($data, $id)
    {
        $this->db->where('w_id', $id);
        if ($this->db->update('warehouse', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_warehouse($id)
    {
        $this->db->where('w_id', $id);
        $this->db->delete('warehouse');
        return true;
    }
    public function get_categories()
    {
        $query = $this->db->order_by('category', 'asc')->get('category');
        return $query->result();
    }
    public function get_products_using_cat($cat_id)
    {
        return $this->db->select('product.*,category.category_id,category.category')
            ->from('product')
            ->join('category', 'product.category_id=category.category_id', 'left')
            ->where('product.category_id', $cat_id)
            ->order_by('product.product_name', 'asc')
            ->get();
    }

    /////////////WAREHOUSE END/////////////


    //////////// INVENTORY //////////////

    public function inventory_list()
    {
        $sql = "
            SELECT
                stock_move_log.*, 
                xin_employees.first_name, 
                xin_employees.last_name,
                
                -- 'from' side description
                CASE
                    WHEN stock_move_log.from_to_type IN ('warehouse to project', 'warehouse to warehouse', 'Warehouse Stock Add') THEN CONCAT('WH/', warehouse_from.w_name)
                    WHEN stock_move_log.from_to_type = 'project to warehouse' THEN CONCAT('PROJECT/', project_from.project_title)
                    WHEN stock_move_log.from_to_type IN ('supplier to warehouse', 'supplier to project') THEN CONCAT('Supplier/', xin_suppliers.supplier_name)
                    WHEN stock_move_log.from_to_type = 'project to project' THEN CONCAT('PROJECT/', project_from.project_title)
                    ELSE ''
                END AS from_description,
                
                -- 'to' side description
                CASE
                    WHEN stock_move_log.from_to_type IN ('warehouse to project', 'supplier to project', 'project to project') THEN CONCAT('PROJECT/', project_to.project_title)
                    WHEN stock_move_log.from_to_type IN ('warehouse to warehouse', 'supplier to warehouse', 'project to warehouse', 'Warehouse Stock Add') THEN CONCAT('WH/', warehouse_to.w_name)
                    ELSE ''
                END AS to_description,
                
                -- relevant_from_id
                CASE
                    WHEN stock_move_log.from_to_type IN ('warehouse to project', 'warehouse to warehouse', 'Warehouse Stock Add') THEN warehouse_from.w_id
                    WHEN stock_move_log.from_to_type = 'project to warehouse' THEN project_from.project_id
                    WHEN stock_move_log.from_to_type IN ('supplier to warehouse', 'supplier to project') THEN xin_suppliers.supplier_id
                    WHEN stock_move_log.from_to_type = 'project to project' THEN project_from.project_id
                    ELSE NULL
                END AS relevant_from_id,
                
                -- relevant_to_id
                CASE
                    WHEN stock_move_log.from_to_type IN ('warehouse to project', 'supplier to project', 'project to project') THEN project_to.project_id
                    WHEN stock_move_log.from_to_type IN ('warehouse to warehouse', 'supplier to warehouse', 'project to warehouse', 'Warehouse Stock Add') THEN warehouse_to.w_id
                    ELSE NULL
                END AS relevant_to_id,
                
                -- Set warehouse_id to 0 for 'project to project'
                CASE
                    WHEN stock_move_log.from_to_type = 'project to project' THEN 0
                    ELSE warehouse_to.w_id
                END AS warehouse_id,
    
                product.product_name
        
            FROM stock_move_log
            
            -- warehouse_from join
            LEFT JOIN warehouse AS warehouse_from
                ON stock_move_log.stock_from = warehouse_from.w_id
                AND stock_move_log.from_to_type IN ('warehouse to project', 'warehouse to warehouse', 'Warehouse Stock Add')
            
            -- warehouse_to join
            LEFT JOIN warehouse AS warehouse_to
                ON stock_move_log.stock_to = warehouse_to.w_id
                AND stock_move_log.from_to_type IN ('warehouse to warehouse', 'supplier to warehouse', 'project to warehouse', 'Warehouse Stock Add')
            
            -- supplier join
            LEFT JOIN xin_suppliers
                ON stock_move_log.stock_from = xin_suppliers.supplier_id
                AND stock_move_log.from_to_type IN ('supplier to warehouse', 'supplier to project')
            
            -- project_from join
            LEFT JOIN projects AS project_from
                ON stock_move_log.stock_from = project_from.project_id
                AND stock_move_log.from_to_type IN ('project to warehouse', 'project to project')
            
            -- project_to join
            LEFT JOIN projects AS project_to
                ON stock_move_log.stock_to = project_to.project_id
                AND stock_move_log.from_to_type IN ('warehouse to project', 'supplier to project', 'project to project')
            
            -- product join
            LEFT JOIN product
                ON stock_move_log.product_id = product.product_id
    
            -- employee/user join
            LEFT JOIN xin_employees
                ON stock_move_log.by_whome = xin_employees.user_id
            
            WHERE stock_move_log.from_to_type IN (
                'warehouse to project', 'supplier to warehouse', 'supplier to project',
                'warehouse to warehouse', 'project to warehouse', 'Warehouse Stock Add',
                'project to project'
            )
            ORDER BY stock_move_log.created_date DESC, stock_move_log.id DESC
        ";

        $query = $this->db->query($sql);

        return $query;
    }


    public function QRscan($project_id = null)
    {
        // Base SQL query
        $sql = "
               SELECT
                    stock_move_log.*, 
                    xin_employees.first_name, 
                    xin_employees.last_name,

                    -- 'from' side description
                    CASE
                        WHEN stock_move_log.from_to_type IN ('warehouse to project', 'warehouse to warehouse', 'Warehouse Stock Add') 
                            THEN CONCAT('WH/', warehouse_from.w_name)
                        WHEN stock_move_log.from_to_type = 'project to warehouse' 
                            THEN CONCAT('PROJECT/', projects_from.project_title)
                        WHEN stock_move_log.from_to_type IN ('supplier to warehouse', 'supplier to project') 
                            THEN CONCAT('Supplier/', xin_suppliers.supplier_name)
                        WHEN stock_move_log.from_to_type = 'project to project' 
                            THEN CONCAT('PROJECT/', projects_from.project_title)
                        ELSE ''
                    END AS from_description,

                    -- 'to' side description
                    CASE
                        WHEN stock_move_log.from_to_type IN ('warehouse to project', 'supplier to project', 'project to project') 
                            THEN CONCAT('PROJECT/', projects_to.project_title)
                        WHEN stock_move_log.from_to_type IN ('warehouse to warehouse', 'supplier to warehouse', 'project to warehouse', 'Warehouse Stock Add') 
                            THEN CONCAT('WH/', warehouse_to.w_name)
                        ELSE ''
                    END AS to_description,

                    -- Handling relevant_from_id and relevant_to_id according to from_to_type
                    CASE
                        WHEN stock_move_log.from_to_type IN ('warehouse to project', 'warehouse to warehouse', 'Warehouse Stock Add') 
                            THEN warehouse_from.w_id
                        WHEN stock_move_log.from_to_type = 'project to warehouse' 
                            THEN projects_from.project_id
                        WHEN stock_move_log.from_to_type IN ('supplier to warehouse', 'supplier to project') 
                            THEN xin_suppliers.supplier_id
                        WHEN stock_move_log.from_to_type = 'project to project' 
                            THEN projects_from.project_id
                        ELSE NULL
                    END AS relevant_from_id,

                    CASE
                        WHEN stock_move_log.from_to_type IN ('warehouse to project', 'supplier to project', 'project to project') 
                            THEN projects_to.project_id
                        WHEN stock_move_log.from_to_type IN ('warehouse to warehouse', 'supplier to warehouse', 'project to warehouse', 'Warehouse Stock Add') 
                            THEN warehouse_to.w_id
                        ELSE NULL
                    END AS relevant_to_id,

                    -- Directly use warehouse_to.w_id for warehouse_id, without setting it to 0
                    warehouse_to.w_id AS warehouse_id,

                    -- Set trans_type for 'project to project'
                    CASE
                        WHEN stock_move_log.from_to_type = 'project to project' 
                            THEN 'OUTBOUND'
                        ELSE stock_move_log.trans_type
                    END AS trans_type,

                    -- Set movement_type for 'project to project'
                    CASE
                        WHEN stock_move_log.from_to_type = 'project to project' 
                            THEN 'Transfer'
                        ELSE stock_move_log.movement_type
                    END AS movement_type,

                    -- Set remark for 'project to project'
                    CASE
                        WHEN stock_move_log.from_to_type = 'project to project' 
                            THEN 'This Item and Quantity Transfer from Project Site Through QR Code'
                        ELSE stock_move_log.remark
                    END AS remark,

                    product.product_name

                FROM stock_move_log

                -- Warehouse joins
                LEFT JOIN warehouse AS warehouse_from 
                    ON stock_move_log.stock_from = warehouse_from.w_id
                    AND stock_move_log.from_to_type IN ('warehouse to project', 'warehouse to warehouse', 'Warehouse Stock Add')

                LEFT JOIN warehouse AS warehouse_to 
                    ON stock_move_log.stock_to = warehouse_to.w_id
                    AND stock_move_log.from_to_type IN ('warehouse to warehouse', 'supplier to warehouse', 'project to warehouse', 'Warehouse Stock Add')

                -- Project joins
                LEFT JOIN projects AS projects_from 
                    ON stock_move_log.stock_from = projects_from.project_id
                    AND stock_move_log.from_to_type IN ('project to warehouse', 'project to project')

                LEFT JOIN projects AS projects_to 
                    ON stock_move_log.stock_to = projects_to.project_id
                    AND stock_move_log.from_to_type IN ('warehouse to project', 'supplier to project', 'project to project')

                -- Supplier joins
                LEFT JOIN xin_suppliers 
                    ON stock_move_log.stock_from = xin_suppliers.supplier_id
                    AND stock_move_log.from_to_type IN ('supplier to warehouse', 'supplier to project')

                -- Product join
                LEFT JOIN product 
                    ON stock_move_log.product_id = product.product_id

                -- Employee join
                LEFT JOIN xin_employees 
                    ON stock_move_log.by_whome = xin_employees.user_id

                WHERE stock_move_log.from_to_type IN ('warehouse to project', 'project to warehouse', 'project to project')

                ORDER BY stock_move_log.created_date DESC, stock_move_log.id DESC;
                ";

        // Add a condition for project_id if provided
        // if ($project_id !== null) {
        //     $sql .= " AND (projects.project_id = " . $this->db->escape($project_id) . " OR projects_from.project_id = " . $this->db->escape($project_id) . " OR projects_to.project_id = " . $this->db->escape($project_id) . ")";
        // }

        // Order by the latest stock_move_log ID
        // $sql .= " ORDER BY stock_move_log.created_date DESC, stock_move_log.id DESC";

        // Execute the query
        $query = $this->db->query($sql);

        // Return the result
        return $query;
    }

    public function stock_out_list($project_id = null)
    {
        // Base SQL query
        $sql = "
        SELECT
            stock_move_log.*, 
            xin_employees.first_name, 
            xin_employees.last_name,
            
            -- 'from' side description for warehouse to project
            CASE
                WHEN stock_move_log.from_to_type = 'warehouse to project' THEN CONCAT('WH/', warehouse_from.w_name)
                ELSE ''
            END AS from_description,
            
            -- 'to' side description for warehouse to project
            CASE
                WHEN stock_move_log.from_to_type = 'warehouse to project' 
                THEN CONCAT('PROJECT/', projects.project_title)
                ELSE ''
            END AS to_description,
            
            -- Handling relevant_from_id and relevant_to_id for warehouse to project
            CASE
                WHEN stock_move_log.from_to_type = 'warehouse to project' THEN warehouse_from.w_id
                ELSE NULL
            END AS relevant_from_id,
            
            CASE
                WHEN stock_move_log.from_to_type = 'warehouse to project' THEN projects.project_id
                ELSE NULL
            END AS relevant_to_id,
            
            product.product_name,
            product.std_uom

        FROM stock_move_log
        
        -- 'warehouse to project' case: JOIN with warehouse for stock_from
        LEFT JOIN warehouse AS warehouse_from
            ON stock_move_log.stock_from = warehouse_from.w_id
            AND stock_move_log.from_to_type = 'warehouse to project'
        
        -- 'warehouse to project' case: JOIN with project for stock_to
        LEFT JOIN projects
            ON stock_move_log.stock_to = projects.project_id
            AND stock_move_log.from_to_type = 'warehouse to project'
        
        -- JOIN with product for product information
        LEFT JOIN product
            ON stock_move_log.product_id = product.product_id

        -- JOIN with xin_employees for user information
        LEFT JOIN xin_employees
            ON stock_move_log.by_whome = xin_employees.user_id
        
        WHERE stock_move_log.from_to_type = 'warehouse to project'";

        // Add a condition for project_id if provided
        if ($project_id !== null) {
            $sql .= " AND projects.project_id = " . $this->db->escape($project_id);
        }

        // Order by the latest stock_move_log ID
        $sql .= " ORDER BY stock_move_log.id DESC";

        // Execute the query
        $query = $this->db->query($sql);

        // Return the result
        return $query->result();
    }

    public function stock_return_list($project_id = null)
    {
        // Base SQL query
        $sql = "
        SELECT
            stock_move_log.*, 
            xin_employees.first_name, 
            xin_employees.last_name,
            
            -- 'from' side description for project to warehouse
            CASE
                WHEN stock_move_log.from_to_type = 'project to warehouse' THEN CONCAT('PROJECT/', projects.project_title)
                ELSE ''
            END AS from_description,
            
            -- 'to' side description for project to warehouse
            CASE
                WHEN stock_move_log.from_to_type = 'project to warehouse' THEN CONCAT('WH/', warehouse_to.w_name)
                ELSE ''
            END AS to_description,
            
            -- Handling relevant_from_id and relevant_to_id for project to warehouse
            CASE
                WHEN stock_move_log.from_to_type = 'project to warehouse' THEN projects.project_id
                ELSE NULL
            END AS relevant_from_id,
            
            CASE
                WHEN stock_move_log.from_to_type = 'project to warehouse' THEN warehouse_to.w_id
                ELSE NULL
            END AS relevant_to_id,
            
            product.product_name,
            product.std_uom

        FROM stock_move_log
        
        -- 'project to warehouse' case: JOIN with project for stock_from
        LEFT JOIN projects
            ON stock_move_log.stock_from = projects.project_id
            AND stock_move_log.from_to_type = 'project to warehouse'
        
        -- 'project to warehouse' case: JOIN with warehouse for stock_to
        LEFT JOIN warehouse AS warehouse_to
            ON stock_move_log.stock_to = warehouse_to.w_id
            AND stock_move_log.from_to_type = 'project to warehouse'
        
        -- JOIN with product for product information
        LEFT JOIN product
            ON stock_move_log.product_id = product.product_id

        -- JOIN with xin_employees for user information
        LEFT JOIN xin_employees
            ON stock_move_log.by_whome = xin_employees.user_id
        
        WHERE stock_move_log.from_to_type = 'project to warehouse'";

        // Add a condition for project_id if provided
        if ($project_id !== null) {
            $sql .= " AND projects.project_id = " . $this->db->escape($project_id);
        }

        // Order by the latest stock_move_log ID
        $sql .= " ORDER BY stock_move_log.id DESC";

        // Execute the query
        $query = $this->db->query($sql);

        // Return the result
        return $query->result();
    }




    public function stock_purchase_list($project_id = null)
    {
        $sql = "
            SELECT
                stock_move_log.*, 
                xin_employees.first_name, 
                xin_employees.last_name,
                
                -- 'from' side description for both cases
                CASE
                    WHEN stock_move_log.from_to_type IN ('supplier to project', 'supplier to warehouse') THEN CONCAT('Supplier/', xin_suppliers.supplier_name)
                    ELSE ''
                END AS from_description,
                
                -- 'to' side description for both cases
                CASE
                    WHEN stock_move_log.from_to_type = 'supplier to project' THEN CONCAT('PROJECT/', projects.project_title)
                    WHEN stock_move_log.from_to_type = 'supplier to warehouse' THEN CONCAT('WAREHOUSE/', warehouse.w_name)
                    ELSE ''
                END AS to_description,
                
                -- Handling relevant_from_id for both cases
                CASE
                    WHEN stock_move_log.from_to_type IN ('supplier to project', 'supplier to warehouse') THEN xin_suppliers.supplier_id
                    ELSE NULL
                END AS relevant_from_id,
                
                -- Handling relevant_to_id for both cases
                CASE
                    WHEN stock_move_log.from_to_type = 'supplier to project' THEN projects.project_id
                    WHEN stock_move_log.from_to_type = 'supplier to warehouse' THEN warehouse.w_id
                    ELSE NULL
                END AS relevant_to_id,
                
                product.product_name,
                product.std_uom
        
            FROM stock_move_log
            
            -- 'supplier to project' and 'supplier to warehouse' cases: JOIN with supplier for stock_from
            LEFT JOIN xin_suppliers
                ON stock_move_log.stock_from = xin_suppliers.supplier_id
                AND stock_move_log.from_to_type IN ('supplier to project', 'supplier to warehouse')
            
            -- 'supplier to project' case: JOIN with project for stock_to
            LEFT JOIN projects
                ON stock_move_log.stock_to = projects.project_id
                AND stock_move_log.from_to_type = 'supplier to project'
            
            -- 'supplier to warehouse' case: JOIN with warehouse for stock_to
            LEFT JOIN warehouse
                ON stock_move_log.stock_to = warehouse.w_id
                AND stock_move_log.from_to_type = 'supplier to warehouse'
            
            -- JOIN with product for product information
            LEFT JOIN product
                ON stock_move_log.product_id = product.product_id
        
            -- JOIN with xin_employees for user information
            LEFT JOIN xin_employees
                ON stock_move_log.by_whome = xin_employees.user_id
            
            WHERE stock_move_log.from_to_type IN ('supplier to project', 'supplier to warehouse')
        ";

        // Add condition for project_id if it's not null
        if ($project_id !== null) {
            $sql .= " AND (stock_move_log.from_to_type = 'supplier to project' AND projects.project_id = " . $this->db->escape($project_id) . ")";
        }

        $sql .= " ORDER BY stock_move_log.id DESC";

        $query = $this->db->query($sql);
        return $query->result();
    }





    //////////// INVENTORY END //////////////
    public function check_product_detail($product_name)
    {
        $query = $this->db->where('product_name', $product_name)
            // ->where('location', $location)
            ->get('product');
        return $query->result();
    }
    public function get_warehouse_category($id)
    {
        $query = $this->db->select('p.*,c.category')
            ->join('grn_item_mapping gm', 'p.product_id=gm.prd_id')
            ->join('category c', 'p.category_id=c.category_id')
            ->join('grn_tbl g', 'gm.grn_id=g.grn_id')
            ->where('g.whouse', $id)
            ->group_by('c.category')
            ->get('product p');
        return $query->result();
    }
    public function temp_inventory_list()
    {
        $query = $this->db->get('tbl_inventory_tracking');
        return $query;
    }
    // Model: Warehouse_model.php
    public function get_stock($warehouse_id, $product_id)
    {
        $this->db->select('quantity');
        $this->db->from('stock_management');
        $this->db->where('warehouse_id', $warehouse_id);
        $this->db->where('prd_id', $product_id);
        $result = $this->db->get()->row();
        return $result ? $result->quantity : 0;
    }

    public function transfer_stock($from_warehouse_id, $to_warehouse_id, $product_id, $quantity)
    {
        // Deduct from the originating warehouse
        $this->db->set('quantity', 'quantity - ' . (int)$quantity, FALSE);
        $this->db->where('warehouse_id', $from_warehouse_id);
        $this->db->where('prd_id', $product_id);
        $this->db->update('stock_management');

        // Add to the destination warehouse
        $this->db->where('warehouse_id', $to_warehouse_id);
        $this->db->where('prd_id', $product_id);
        $exists = $this->db->get('stock_management')->num_rows() > 0;

        if ($exists) {
            // Update existing record
            $this->db->set('quantity', 'quantity + ' . (int)$quantity, FALSE);
            $this->db->where('warehouse_id', $to_warehouse_id);
            $this->db->where('prd_id', $product_id);
            $this->db->update('stock_management');
        } else {
            // Insert new record for the destination warehouse
            $this->db->insert('stock_management', [
                'warehouse_id' => $to_warehouse_id,
                'prd_id' => $product_id,
                'quantity' => $quantity
            ]);
        }
    }
}
