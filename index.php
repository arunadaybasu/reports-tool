<?php
require_once('dbinfo.php');
include 'header.php';
?>

  <div class="container">
    <div class="section">

      <div class="row form-row">
        <form class="col s12" id="filter-unassigned-form" action="filter-unassigned.php" method="post">
          <div class="row">
            <div class="input-field col s12 m6 franchise-select-div">
              <select id="franchise-select" name="franchise[]" multiple disabled>
                <option value="" disabled selected>Franchises</option>
              </select>
              <label>Select Franchises</label>
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

      <div class="row">
        
        <h5 class="pad-bot center-align">Unassigned Appointments Report</h5>

        <div class="col s12 m12">
          <h6 class="pad-bot center-align">Unassigned Appointments (Time Slots - Bar)</h6>
          <div id="chart-container1">
            <canvas id="myChart1"></canvas>
          </div>
        </div>

      </div>

      <div class="row pad-top-big">
        <div class="col s12 m4">
          <h6 class="pad-bot center-align">Unassigned Appointments (Time Slots - Doughnut)</h6>
          <div id="chart-container2">
            <canvas id="myChart2"></canvas>
          </div>

        </div>

        <div class="col s12 m8">
          <h6 class="pad-bot center-align">Unassigned Appointments (Franchises - Bar)</h6>
          <div id="chart-container3">
            <canvas id="myChart3"></canvas>
          </div>
        </div>
      </div>

      <div class="row">
        
        <div class="col s12 m12">
          <h6 class="pad-bot center-align">Unassigned Appointments (Daily/Monthly/Yearly - Bar)</h6>
          <div id="chart-container4">
            <canvas id="myChart4"></canvas>
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