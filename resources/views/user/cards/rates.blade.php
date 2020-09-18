<script>var chart_rates = [{!! implode(', ', $chart_rates) !!}];</script>
<script src="/data/vendor/chart/Chart.js"></script>
<script src="/data/js/rates_chart.js"></script>
<div class="card bg-light">
    <div class="card-body">
        <canvas id="chart_rates"></canvas>
    </div>
</div>
