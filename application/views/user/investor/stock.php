<style>
    .stock-table-wrapper{
    background:#fff;
    border-radius:12px;
    padding:20px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    margin-bottom:25px;
    margin-left: -10px;
}

.stock-table thead th{
    background:#f5f5f5;
    font-weight:700;
    text-align:center;
    vertical-align:middle;
}

.stock-table td{
    vertical-align:middle;
    font-size:14px;
    color:#444;
}

.stock-table td.symbol{
    font-weight:700;
    color:#bf2d37;
    text-align:center;
}

.stock-table tr:hover{
    background:#fafafa;
}

@media(max-width:768px){
    .stock-table-wrapper{
        overflow-x:auto;
    }
}
</style>
<div class="stock-table-wrapper">
    <table class="table table-bordered stock-table">
        <thead>
            <tr>
                <th>Name Of Stock Exchange</th>
                <th>Address</th>
                <th>Stock Codes / Symbols</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($stock as $row){ ?>
            <tr>
                <td><?= $row['name']; ?></td>
                <td><?= $row['address']; ?></td>
                <td class="symbol"><?= $row['symbol']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>