/**
 * Dashboard JavaScript
 * 
 * Este arquivo controla a funcionalidade da página de dashboard,
 * incluindo gráficos, filtros de data e atualizações dinâmicas.
 */

// Função principal para inicializar o dashboard
function initDashboard(chartData) {
    // Configurar o gráfico principal de vendas
    setupSalesChart(chartData);
    
    // Configurar os filtros de período
    setupPeriodFilters();
    
    // Configurar o seletor de data personalizado
    setupCustomDateSelector();
}

// Configurar o gráfico de vendas
function setupSalesChart(chartData) {
    const ctx = document.getElementById('salesChart');
    
    // Verificar se temos dados e o elemento canvas existe
    if (!ctx || !chartData || !chartData.labels) {
        console.warn('Dados do gráfico ou elemento canvas não encontrados');
        return;
    }
    
    // Criar o gráfico
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels || [],
            datasets: [
                {
                    label: 'Vendas',
                    data: chartData.vendas || [],
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    fill: false
                },
                {
                    label: 'Custos',
                    data: chartData.custos || [],
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Vendas e Custos'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    }
                }
            }
        }
    });
}

// Configurar os filtros de período
function setupPeriodFilters() {
    const periodButtons = document.querySelectorAll('.period-btn');
    const customSelector = document.getElementById('custom-date-selector');
    
    // Adicionar eventos de clique aos botões de período
    periodButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remover classe ativa de todos os botões
            periodButtons.forEach(btn => btn.classList.remove('active'));
            
            // Adicionar classe ativa ao botão clicado
            this.classList.add('active');
            
            // Mostrar/esconder seletor de data personalizado
            if (this.id === 'period-custom') {
                customSelector.style.display = 'block';
            } else {
                customSelector.style.display = 'none';
                
                // Fazer solicitação para atualizar dados com o período selecionado
                updateDashboardData(this.id.replace('period-', ''));
            }
        });
    });
}

// Configurar o seletor de data personalizado
function setupCustomDateSelector() {
    const applyButton = document.getElementById('apply-date-range');
    const startDate = document.getElementById('start-date');
    const endDate = document.getElementById('end-date');
    
    // Configurar data inicial com o mês atual
    const today = new Date();
    endDate.value = today.toISOString().split('T')[0];
    
    // Configurar data inicial com o primeiro dia do mês
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    startDate.value = firstDay.toISOString().split('T')[0];
    
    // Adicionar evento ao botão de aplicar
    applyButton.addEventListener('click', function() {
        if (startDate.value && endDate.value) {
            updateDashboardData('custom', {
                start: startDate.value,
                end: endDate.value
            });
        } else {
            alert('Por favor, selecione as datas inicial e final.');
        }
    });
}

// Atualizar os dados do dashboard com base no período selecionado
function updateDashboardData(period, customDates = null) {
    // Mostrar indicador de carregamento
    showLoading();
    
    // Construir URL para solicitação
    let url = '/dashboard/data?period=' + period;
    
    if (period === 'custom' && customDates) {
        url += '&start=' + customDates.start + '&end=' + customDates.end;
    }
    
    // Fazer solicitação AJAX para obter novos dados
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao obter dados do dashboard');
            }
            return response.json();
        })
        .then(data => {
            // Atualizar os valores do dashboard
            updateDashboardValues(data);
            
            // Atualizar o gráfico
            updateChart(data.chartData);
            
            // Ocultar indicador de carregamento
            hideLoading();
        })
        .catch(error => {
            console.error('Erro:', error);
            hideLoading();
            alert('Ocorreu um erro ao atualizar os dados do dashboard.');
        });
}

// Atualizar os valores do dashboard
function updateDashboardValues(data) {
    // Atualizar valores financeiros
    document.getElementById('total-receita').textContent = formatCurrency(data.totalReceita || 0);
    document.getElementById('total-despesas').textContent = formatCurrency(data.totalDespesas || 0);
    document.getElementById('total-lucro').textContent = formatCurrency(data.totalLucro || 0);
    document.getElementById('margem-lucro').textContent = formatPercentage(data.margemLucro || 0);
    
    // Atualizar o card de lucro (positivo ou negativo)
    const lucroCard = document.getElementById('lucro-card');
    lucroCard.className = 'dashboard-card ' + ((data.totalLucro >= 0) ? 'positive' : 'negative');
    
    // Atualizar produtos mais vendidos
    updateTopProducts(data.produtosMaisVendidos || []);
    
    // Atualizar vendas por categoria
    updateCategorySales(data.vendasPorCategoria || []);
}

// Atualizar a tabela de produtos mais vendidos
function updateTopProducts(produtos) {
    const container = document.getElementById('top-products');
    let content = '<h3>Produtos Mais Vendidos</h3>';
    
    if (produtos.length > 0) {
        content += `
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Total (R$)</th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        produtos.forEach(produto => {
            content += `
                <tr>
                    <td>${produto.nome}</td>
                    <td>${produto.quantidade_vendida}</td>
                    <td>${formatCurrency(produto.total_vendido)}</td>
                </tr>
            `;
        });
        
        content += '</tbody></table>';
    } else {
        content += '<p>Nenhum produto vendido no período</p>';
    }
    
    container.innerHTML = content;
}

// Atualizar a tabela de vendas por categoria
function updateCategorySales(categorias) {
    const container = document.getElementById('category-sales');
    let content = '<h3>Vendas por Categoria</h3>';
    
    if (categorias.length > 0) {
        content += `
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Categoria</th>
                        <th>Qtd. Produtos</th>
                        <th>Total (R$)</th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        categorias.forEach(categoria => {
            content += `
                <tr>
                    <td>${categoria.nome}</td>
                    <td>${categoria.quantidade_vendida}</td>
                    <td>${formatCurrency(categoria.total_vendido)}</td>
                </tr>
            `;
        });
        
        content += '</tbody></table>';
    } else {
        content += '<p>Nenhuma venda por categoria no período</p>';
    }
    
    container.innerHTML = content;
}

// Atualizar o gráfico com novos dados
function updateChart(chartData) {
    const chartElement = Chart.getChart('salesChart');
    
    if (chartElement) {
        chartElement.data.labels = chartData.labels || [];
        chartElement.data.datasets[0].data = chartData.vendas || [];
        chartElement.data.datasets[1].data = chartData.custos || [];
        chartElement.update();
    } else {
        setupSalesChart(chartData);
    }
}

// Funções auxiliares
function formatCurrency(value) {
    return 'R$ ' + Number(value).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function formatPercentage(value) {
    return Number(value).toLocaleString('pt-BR', {
        minimumFractionDigits: 1,
        maximumFractionDigits: 1
    }) + '%';
}

function showLoading() {
    // Adicionar classe de loading ou mostrar um spinner
    document.getElementById('dashboard-section').classList.add('loading');
}

function hideLoading() {
    // Remover classe de loading ou ocultar o spinner
    document.getElementById('dashboard-section').classList.remove('loading');
}
