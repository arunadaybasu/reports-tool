<?php
require_once('dbinfo.php');
include 'header.php';
?>

  <div class="container">
    <div class="section">

      <div class="row form-row">
        <form class="col s12" id="filter-discount-form" action="filter-discount.php" method="post">
          <div class="row">
            <div class="input-field col s12 m4 franchise-select-div">
              <select id="category-select" name="category[]" multiple disabled>
                <option value="" disabled selected>Categories</option>
              </select>
              <label>Select Categories</label>
            </div>
            <div class="input-field col s12 m2">
                <select id="status-select" name="status[]" multiple disabled>
                  <option value="" disabled selected>Status</option>
                </select>
                <label>Select Status</label>
            </div>
            <div class="input-field col s12 m2">
              <input type="date" class="datepicker" id="date-from" name="date-from">
              <label for="date-from">Date From</label>
            </div>
            <div class="input-field col s12 m2">
              <input type="date" class="datepicker" id="date-to" name="date-to">
              <label for="date-to">Date To</label>
            </div>
            <div class="input-field col s12 m2">
              <button class="btn waves-effect waves-light" type="submit" name="action">Submit
                <i class="material-icons right">send</i>
              </button>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>

  <div class="container">
    <div class="section">

      <!--   Icon Section   -->
      <div class="row">
        
        <h5 class="pad-bot center-align">Discounts and Revenue Report</h5>

        <div class="col s12 m12">
          <h6 class="pad-bot center-align">Total Discount (Daily/Monthly/Yearly - Bar)</h6>
          <div id="chart-container1">
            <canvas id="myChart1"></canvas>
          </div>
        </div>

      </div>

      <div class="row pad-top-big">
        <div class="col s12 m4">
          <h6 class="pad-bot center-align">Cancelled/Rejected Appointments (Number of Appointments - Doughnut)</h6>
          <div id="chart-container2">
            <canvas id="myChart2"></canvas>
          </div>

        </div>

        <div class="col s12 m8">
          <h6 class="pad-bot center-align">Cancelled/Rejected Reasons (Reasons - Bar)</h6>
          <div id="chart-container3">
            <canvas id="myChart3"></canvas>
          </div>
        </div>
      </div>

    </div>
    <br><br>

    <div class="section">

    </div>
  </div>

<?php
include 'footer.php';
?>