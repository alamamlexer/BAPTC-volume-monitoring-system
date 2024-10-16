document.addEventListener('DOMContentLoaded', function() {
    // Load Page function
    function loadPage(page) {
        const url = `/your-route-url?page=${page}`; // Change this to your route for fetching data

        fetch(url)
            .then(response => response.text())
            .then(data => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, 'text/html');
                const newTableBody = doc.querySelector('#inflowTableBody');
                const newPagination = doc.querySelector('.pagination');

                // Update the table body and pagination
                document.querySelector('#inflowTableBody').innerHTML = newTableBody.innerHTML;
                document.querySelector('.pagination').innerHTML = newPagination.innerHTML;

                // Prevent the page from jumping to the top
                scrollToTable();
            })
            .catch(error => console.error('Error loading page:', error));
    }

    // Function to smoothly scroll to the table position
    function scrollToTable() {
        const table = document.querySelector('.table-responsive');
        if (table) {
            table.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Event delegation for pagination links
    document.addEventListener('click', function (e) {
        if (e.target.closest('.page-link')) {
            e.preventDefault(); // Prevent default anchor click behavior
            const page = e.target.getAttribute('data-page');
            if (page) {
                loadPage(page);
            }
        }
    });

    // Filters setup
    const amPmFilter = document.getElementById('amPmFilter');
    const attendantFilter = document.getElementById('attendantFilter');
    const commodityFilter = document.getElementById('commodityFilter');
    const productionOriginFilter = document.getElementById('productionOriginFilter');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const tableRows = document.querySelectorAll('#inflowTableBody tr');

    function filterTable() {
        const selectedAmPm = amPmFilter.value;
        const selectedAttendant = attendantFilter.value;
        const selectedCommodity = commodityFilter.value;
        const selectedProductionOrigin = productionOriginFilter.value;
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        tableRows.forEach(row => {
            const rowDate = new Date(row.dataset.date);
            const rowAmPm = row.dataset.amPm;
            const rowAttendant = row.dataset.attendant;
            const rowCommodity = row.dataset.commodity;
            const rowProductionOrigin = row.dataset.productionOrigin;

            const isDateInRange = (!startDateInput.value || rowDate >= startDate) && (!endDateInput.value || rowDate <= endDate);
            const isAmPmMatch = !selectedAmPm || (rowAmPm.startsWith(selectedAmPm));
            const isAttendantMatch = !selectedAttendant || (rowAttendant === selectedAttendant);
            const isCommodityMatch = !selectedCommodity || (rowCommodity === selectedCommodity);
            const isProductionOriginMatch = !selectedProductionOrigin || (rowProductionOrigin.includes(selectedProductionOrigin));

            if (isDateInRange && isAmPmMatch && isAttendantMatch && isCommodityMatch && isProductionOriginMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    amPmFilter.addEventListener('change', filterTable);
    attendantFilter.addEventListener('change', filterTable);
    commodityFilter.addEventListener('change', filterTable);
    productionOriginFilter.addEventListener('change', filterTable);
    startDateInput.addEventListener('change', filterTable);
    endDateInput.addEventListener('change', filterTable);

    // Reset filters button
    document.getElementById('resetFilters').addEventListener('click', () => {
        amPmFilter.value = '';
        attendantFilter.value = '';
        commodityFilter.value = '';
        productionOriginFilter.value = '';
        startDateInput.value = '';
        endDateInput.value = '';
        filterTable(); // Apply reset
    });

    // Initialize Trading Inflow Chart
    const totalVolumeData = window.totalVolumeData; // Ensure this is defined in your HTML
    const series = window.chartData; // Ensure this is defined in your HTML
    const dates = window.dates; // Ensure this is defined in your HTML

    // Add Total Volume as the first series
    const combinedSeries = [{
        name: 'Total Volume',
        data: totalVolumeData
    }, ...series];

    const inflowChart = new ApexCharts(document.querySelector("#areaChart"), {
        series: combinedSeries,
        chart: {
            type: 'line',
            height: 350,
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'straight'
        },
        subtitle: {
            text: 'Volume of Trading Inflows by Commodity',
            align: 'left'
        },
        labels: dates,
        xaxis: {
            type: 'datetime'
        },
        yaxis: {
            opposite: true
        },
        legend: {
            horizontalAlign: 'left'
        }
    });

    inflowChart.render();

    // Check for success message in session (this will need to be handled differently)
    if (window.sessionSuccess) {
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
        setTimeout(function() {
            successModal.hide();
        }, 1000); // 1 second timeout
    }

    // Check for error message in session (this will need to be handled differently)
    if (window.sessionError) {
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
        setTimeout(function() {
            errorModal.hide();
        }, 1000); // 1 second timeout
    }
});
