// Aura Tac — robust chart rendering for Livewire (initial load, wire:navigate, and updates)
(function () {
    function renderCharts() {
        if (typeof Chart === 'undefined') return;
        document.querySelectorAll('canvas[data-chart]').forEach(function (cv) {
            try {
                var cfg = JSON.parse(cv.getAttribute('data-chart'));
                if (cv._chart) { cv._chart.destroy(); }
                cv._chart = new Chart(cv, cfg);
            } catch (e) {
                console.error('Chart render error:', e);
            }
        });
    }

    window.atRenderCharts = renderCharts;

    document.addEventListener('DOMContentLoaded', renderCharts);
    document.addEventListener('livewire:navigated', renderCharts);

    document.addEventListener('livewire:init', function () {
        if (window.Livewire) {
            // redraw after a component refresh (e.g. report date filters)
            window.Livewire.on('charts-updated', function () {
                setTimeout(renderCharts, 50);
            });
        }
    });
})();
