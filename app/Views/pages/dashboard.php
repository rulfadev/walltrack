<?= $this->extend('/layout'); ?>
<?= $this->section('content'); ?>

<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Dashboard Keuangan</h2>
            <p class="text-muted mb-0">
                Ringkasan wallet, transaksi, budget, dan aktivitas keuangan bulan ini.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="<?= base_url('/budgets/create'); ?>" class="btn btn-outline-primary">
                <i class="bi bi-piggy-bank me-1"></i>
                Tambah Budget
            </a>

            <a href="<?= base_url('/transactions/create'); ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                Tambah Transaksi
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Saldo</p>
                    <h4 class="fw-bold mb-0" id="totalBalance">Rp0</h4>
                    <small class="text-muted">Semua wallet</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Pemasukan</p>
                    <h4 class="fw-bold mb-0 text-success" id="totalIncome">Rp0</h4>
                    <small class="text-muted" id="monthIncomeLabel">Bulan ini</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Pengeluaran</p>
                    <h4 class="fw-bold mb-0 text-danger" id="totalExpense">Rp0</h4>
                    <small class="text-muted" id="monthExpenseLabel">Bulan ini</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Transfer</p>
                    <h4 class="fw-bold mb-0 text-primary" id="totalTransfer">Rp0</h4>
                    <small class="text-muted" id="monthTransferLabel">Bulan ini</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">Budget Bulanan</h5>
                            <p class="text-muted mb-0" id="budgetMonthLabel">Bulan ini</p>
                        </div>

                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3">
                            <i class="bi bi-piggy-bank"></i>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="text-muted mb-1">Total Budget</p>
                        <h4 class="fw-bold mb-0" id="totalBudget">Rp0</h4>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="border rounded-3 p-3">
                                <p class="text-muted mb-1 small">Terpakai</p>
                                <div class="fw-bold text-danger" id="totalBudgetSpent">Rp0</div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="border rounded-3 p-3">
                                <p class="text-muted mb-1 small">Sisa</p>
                                <div class="fw-bold text-success" id="totalBudgetRemaining">Rp0</div>
                            </div>
                        </div>
                    </div>

                    <a href="<?= base_url('/budgets'); ?>" class="btn btn-sm btn-outline-primary mt-3">
                        Lihat Budget
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">Status Budget</h5>
                            <p class="text-muted mb-0">
                                Kategori dengan pemakaian budget tertinggi bulan ini.
                            </p>
                        </div>

                        <a href="<?= base_url('/budgets/create'); ?>" class="btn btn-sm btn-primary">
                            Tambah Budget
                        </a>
                    </div>

                    <div id="budgetProgressList" class="d-flex flex-column gap-3">
                        <div class="text-muted text-center py-4">
                            Memuat data budget...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-1">Cashflow Bulanan</h5>
                    <p class="text-muted mb-3">Perbandingan pemasukan dan pengeluaran.</p>

                    <div style="height: 320px;">
                        <canvas id="cashflowChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-1">Pengeluaran per Kategori</h5>
                    <p class="text-muted mb-3">Kategori pengeluaran terbesar bulan ini.</p>

                    <div style="height: 280px;">
                        <canvas id="categoryChart"></canvas>
                    </div>

                    <div id="categoryEmpty" class="text-center text-muted py-5 d-none">
                        <i class="bi bi-pie-chart fs-1 d-block mb-2"></i>
                        Belum ada pengeluaran bulan ini.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">Wallet Saya</h5>
                            <p class="text-muted mb-0">Saldo tiap wallet.</p>
                        </div>

                        <a href="<?= base_url('/wallets'); ?>" class="btn btn-sm btn-outline-primary">
                            Lihat Semua
                        </a>
                    </div>

                    <div id="walletList" class="d-flex flex-column gap-3">
                        <div class="text-muted text-center py-4">
                            Memuat data wallet...
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">Transaksi Terbaru</h5>
                            <p class="text-muted mb-0">Aktivitas keuangan terakhir.</p>
                        </div>

                        <a href="<?= base_url('/transactions'); ?>" class="btn btn-sm btn-outline-primary">
                            Lihat Semua
                        </a>
                    </div>

                    <div id="recentTransactions" class="table-responsive">
                        <div class="text-muted text-center py-4">
                            Memuat transaksi terbaru...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const rupiah = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    });

    let cashflowChart = null;
    let categoryChart = null;

    function renderSummary(summary) {
        document.getElementById('totalBalance').textContent = rupiah.format(summary.total_balance || 0);
        document.getElementById('totalIncome').textContent = rupiah.format(summary.total_income || 0);
        document.getElementById('totalExpense').textContent = rupiah.format(summary.total_expense || 0);
        document.getElementById('totalTransfer').textContent = rupiah.format(summary.total_transfer || 0);

        document.getElementById('monthIncomeLabel').textContent = summary.month_label || 'Bulan ini';
        document.getElementById('monthExpenseLabel').textContent = summary.month_label || 'Bulan ini';
        document.getElementById('monthTransferLabel').textContent = summary.month_label || 'Bulan ini';
    }

    function renderBudgetSummary(summary) {
        document.getElementById('budgetMonthLabel').textContent = summary.month_label || 'Bulan ini';
        document.getElementById('totalBudget').textContent = rupiah.format(summary.total_budget || 0);
        document.getElementById('totalBudgetSpent').textContent = rupiah.format(summary.total_spent || 0);

        const remainingElement = document.getElementById('totalBudgetRemaining');
        const remaining = summary.total_remaining || 0;

        remainingElement.textContent = rupiah.format(remaining);
        remainingElement.className = 'fw-bold ' + (remaining >= 0 ? 'text-success' : 'text-danger');
    }

    function renderBudgetProgress(items) {
        const wrapper = document.getElementById('budgetProgressList');

        if (!items || items.length === 0) {
            wrapper.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="bi bi-piggy-bank fs-1 d-block mb-2"></i>
                    Belum ada budget bulan ini.
                    <div class="mt-3">
                        <a href="<?= base_url('/budgets/create'); ?>" class="btn btn-sm btn-primary">
                            Tambah Budget
                        </a>
                    </div>
                </div>
            `;
            return;
        }

        wrapper.innerHTML = items.map(item => {
            const statusClass = item.status?.class || 'secondary';
            const statusLabel = item.status?.label || '-';

            return `
                <div class="border-bottom pb-3">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span
                                class="d-inline-flex align-items-center justify-content-center rounded-circle text-white"
                                style="width: 36px; height: 36px; background: ${escapeHtml(item.category_color || '#134686')};"
                            >
                                <i class="bi ${escapeHtml(item.category_icon || 'bi-tag')}"></i>
                            </span>

                            <div>
                                <div class="fw-semibold">${escapeHtml(item.category_name)}</div>
                                <small class="text-muted">
                                    ${rupiah.format(item.spent || 0)} dari ${rupiah.format(item.amount || 0)}
                                </small>
                            </div>
                        </div>

                        <span class="badge bg-${statusClass}">
                            ${statusLabel}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Progress</small>
                        <small class="fw-semibold">${item.raw_percentage || 0}%</small>
                    </div>

                    <div class="progress" style="height: 9px;">
                        <div
                            class="progress-bar bg-${statusClass}"
                            role="progressbar"
                            style="width: ${item.percentage || 0}%;"
                            aria-valuenow="${item.percentage || 0}"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        ></div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function renderWallets(wallets) {
        const wrapper = document.getElementById('walletList');

        if (!wallets || wallets.length === 0) {
            wrapper.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="bi bi-wallet2 fs-1 d-block mb-2"></i>
                    Belum ada wallet.
                    <div class="mt-3">
                        <a href="<?= base_url('/wallets/create'); ?>" class="btn btn-sm btn-primary">
                            Tambah Wallet
                        </a>
                    </div>
                </div>
            `;
            return;
        }

        wrapper.innerHTML = wallets.map(wallet => `
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
                <div>
                    <div class="fw-semibold">
                        ${escapeHtml(wallet.name)}
                        ${wallet.is_default === 1 ? '<span class="badge bg-primary ms-1">Utama</span>' : ''}
                    </div>
                    <small class="text-muted">${escapeHtml(walletTypeLabel(wallet.type))}</small>
                </div>
                <div class="fw-bold">
                    ${rupiah.format(wallet.current_balance || 0)}
                </div>
            </div>
        `).join('');
    }

    function renderRecentTransactions(transactions) {
        const wrapper = document.getElementById('recentTransactions');

        if (!transactions || transactions.length === 0) {
            wrapper.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                    Belum ada transaksi.
                </div>
            `;
            return;
        }

        wrapper.innerHTML = `
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Jenis</th>
                        <th>Wallet</th>
                        <th>Kategori/Tujuan</th>
                        <th>Nominal</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    ${transactions.map(transaction => {
            const type = transactionTypeLabel(transaction.type);
            const destination = transaction.type === 'transfer'
                ? `${escapeHtml(transaction.wallet_name || '-')} → ${escapeHtml(transaction.target_wallet_name || '-')}`
                : escapeHtml(transaction.category_name || '-');

            return `
                            <tr>
                                <td>
                                    <span class="badge bg-${type.className}">
                                        ${type.label}
                                    </span>
                                </td>
                                <td>${escapeHtml(transaction.wallet_name || '-')}</td>
                                <td>${destination}</td>
                                <td class="fw-semibold">${rupiah.format(transaction.amount || 0)}</td>
                                <td>${formatDate(transaction.transaction_date)}</td>
                            </tr>
                        `;
        }).join('')}
                </tbody>
            </table>
        `;
    }

    function renderCashflowChart(cashflow) {
        const ctx = document.getElementById('cashflowChart');

        if (cashflowChart) {
            cashflowChart.destroy();
        }

        cashflowChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: cashflow.dates || [],
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: cashflow.income || [],
                        tension: 0.35
                    },
                    {
                        label: 'Pengeluaran',
                        data: cashflow.expense || [],
                        tension: 0.35
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `${context.dataset.label}: ${rupiah.format(context.raw || 0)}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function (value) {
                                return rupiah.format(value);
                            }
                        }
                    }
                }
            }
        });
    }

    function renderCategoryChart(categories) {
        const ctx = document.getElementById('categoryChart');
        const empty = document.getElementById('categoryEmpty');

        if (categoryChart) {
            categoryChart.destroy();
        }

        if (!categories || categories.length === 0) {
            ctx.classList.add('d-none');
            empty.classList.remove('d-none');
            return;
        }

        ctx.classList.remove('d-none');
        empty.classList.add('d-none');

        categoryChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: categories.map(item => item.category_name),
                datasets: [
                    {
                        data: categories.map(item => item.total)
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `${context.label}: ${rupiah.format(context.raw || 0)}`;
                            }
                        }
                    }
                }
            }
        });
    }

    function transactionTypeLabel(type) {
        const labels = {
            income: {
                label: 'Pemasukan',
                className: 'success'
            },
            expense: {
                label: 'Pengeluaran',
                className: 'danger'
            },
            transfer: {
                label: 'Transfer',
                className: 'primary'
            }
        };

        return labels[type] || {
            label: type,
            className: 'secondary'
        };
    }

    function walletTypeLabel(type) {
        const labels = {
            cash: 'Cash',
            bank: 'Bank',
            ewallet: 'E-Wallet',
            saving: 'Tabungan'
        };

        return labels[type] || type;
    }

    function formatDate(dateString) {
        if (!dateString) {
            return '-';
        }

        return new Date(dateString).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    fetch('<?= base_url('/api/data'); ?>', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            renderSummary(data.summary || {});
            renderBudgetSummary(data.budget_summary || {});
            renderBudgetProgress(data.budget_progress || []);
            renderWallets(data.wallets || []);
            renderRecentTransactions(data.recent_transactions || []);
            renderCashflowChart(data.cashflow || {});
            renderCategoryChart(data.category_expenses || []);
        })
        .catch(() => {
            document.getElementById('budgetProgressList').innerHTML = `
            <div class="text-danger text-center py-4">
                Gagal memuat data budget.
            </div>
        `;

            document.getElementById('walletList').innerHTML = `
            <div class="text-danger text-center py-4">
                Gagal memuat data wallet.
            </div>
        `;

            document.getElementById('recentTransactions').innerHTML = `
            <div class="text-danger text-center py-4">
                Gagal memuat transaksi terbaru.
            </div>
        `;
        });
</script>

<?= $this->endSection(); ?>