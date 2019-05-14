<div class="card-deck container-fluid homePage-card2">
    <div class="card">
        <div class="card-header text-center bg-primary">
            <i class="fas fa-eye fa-5x"></i>
        </div>
        <div class="card-body">
            <h5 class="card-title"><?php echo $words['homePage']['userPage1Title'][$lang]?></h5>
            <p class="card-text"><?php echo $words['homePage']['userPage1Content'][$lang]?></p>
        </div>
        <div class="card-footer">
            <a href="roles/pages/userPage1.php" class="btn btn-primary">-&gt; <?php echo $words['homePage']['pageGo'][$lang]?> &lt;-</a>
        </div>
    </div>
    <div class="card">
        <div class="card-header text-center bg-danger">
            <i class="fas fa-divide fa-5x"></i>
        </div>
        <div class="card-body">
            <h5 class="card-title"><?php echo $words['homePage']['userPage2Title'][$lang]?></h5>
            <p class="card-text"><?php echo $words['homePage']['userPage2Content'][$lang]?></p>
        </div>
        <div class="card-footer">
            <a href="roles/pages/userPage2.php" class="btn btn-danger">-&gt; <?php echo $words['homePage']['pageGo'][$lang]?> &lt;-</a>
        </div>
    </div>
</div>
