<table id="tbl-book-shelf-list" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Shelf Name</th>
                <th>Capacity</th>
                <th>Shelf Location</th>
                <th>Status</th>
                <th>Create At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            global $wpdb;

            $table_name = $wpdb->prefix . 'owt_tbl_books_shelf';
            $results = $wpdb->get_results(
                "SELECT * FROM $table_name",ARRAY_A

            );

            $i = 1;
            foreach ($results as $item) { ?>
                <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $item['shelf_name'] ?></td>
                <td><?php echo $item['capacity'] ?></td>
                <td><?php echo $item['shelf_location'] ?></td>
                <td><?php 
                if ($item['status']) { ?>
                    <button class="btn btn-success">Active</button>

                    <?php
                    
                } else { ?>

                    <button class="btn btn-light">Inactive</button>
                    <?php 

                }
                 
            ?></td>
                <td><?php echo $item['create_at'] ?></td>
                <td>
                    <button data-id="<?php echo $item['id'] ?>" class="btn btn-danger btn-delete-book-shelf">Delete</button>
                </td>
                </tr>

                <?php $i++;
                
            }

             ?>
            
            
        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Shelf Name</th>
                <th>Capacity</th>
                <th>Shelf Location</th>
                <th>Status</th>
                <th>Create At</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>