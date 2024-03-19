<table id="tbl-book-list" class="display" style="width:100%">
   
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Image</th>
                <th>Language</th>
                <th>Shelf</th>
                <th>Status</th>
                <th>上架时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
             <?php 
    global $wpdb;
    $books = $wpdb->get_results(
        "SELECT book.*,book_shelf.shelf_name FROM " . BOOKS_TABLE . " as book LEFT JOIN " . SHELF_TABLE . " as book_shelf ON book.shelf_id = book_shelf.id",
        ARRAY_A);
    if (count($books)) {
         $i = 1;
    foreach ($books as $row) { ?>

        <tr>
                <td><?php echo $i ?></td>
                <td><?php echo $row['name'] ?></td>
                <td><?php echo $row['amount'] ?></td>
                <td><?php echo $row['description'] ?></td>
                <td>
                    <?php if (!empty($row['book_image'])) { ?>
                        <img src="<?php echo $row['book_image'] ?>" alt="" width=96 heigh=96>

                        <?php 
                        
                    } else {
                        echo '<i>No image found</i>';
                    } ?>
                    
                    
                        
                    </td>
                <td><?php echo $row['language'] ?></td>
                <td><?php echo !empty($row['shelf_name']) ? $row['shelf_name'] : "<i>No shelf selected</i>" ?></td>
                <td>
                    <?php echo $row['status'] === '1' ? '<button class="btn btn-success">Active</button>' : '<button>Inactive</button>'; ?>
                        
                    </td>
                <td><?php echo $row['create_at'] ?></td>
                <td><button data-id="<?php echo $row['id'] ?>" class="btn btn-danger btn-delete-book">Delete</button></td>
            </tr>

        <?php 
        $i++;
    }
        
    }
   


     ?>
            
        </tbody>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Age</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </tfoot>
    </table>