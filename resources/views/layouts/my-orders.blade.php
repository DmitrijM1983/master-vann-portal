<!-- Модальное окно Мои заказы -->
@if (Auth::check())
    <div id="user-orders-window" class="profile" style="display:none; z-index: 1000; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5);">
        <div class="profile-master-content" style="padding-left: 40px; padding-right: 40px; background: white; margin: auto; width: 80%; max-width: 900px;">
            <span class="profile-close" onclick="closeUserOrdersWindow()">&times;</span>

            @if (count($orders) > 0)
                <div class="get-a-quote" style="width: 100%">
                    <div class="center-div" style="padding-bottom: 20px">
                        <h3 style="margin-top: 20px">Заказы</h3>
                    </div>
                    @foreach ($orders as $order)
                        <div class="my-orders">
                            <div>
                                <div class="img-title">
                                    <label for="img-title">Дата заявки</label>
                                    <p style="padding: 0">{{ $order->created_at->format('d.m.Y') }}</p>
                                </div>
                                <div class="img-title">
                                    <label for="img-title">Имя мастера</label>
                                    <p style="padding: 0">{{ $order->user->name }}</p>
                                </div>
                            </div>
                            <div>
                                <label for="img-description">Сообщение</label>
                                <p style="padding: 0">{{ $order->content }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="center-div">
                    <h3 style="padding-top: 20px">Заявок нет</h3>
                </div>
            @endif
        </div>
    </div>
@endif

<script>
    const headerElement = document.querySelector('header'); // Убедитесь, что корректно выбираете заголовок

    function openUserOrdersWindow() {
        document.getElementById('user-orders-window').style.display = "block";
        headerElement.style.display = 'none'; // Скрыть заголовок
        document.body.style.overflow = 'hidden';
    }

    function closeUserOrdersWindow() {
        document.getElementById('user-orders-window').style.display = "none";
        headerElement.style.display = ''; // Показываем заголовок
        document.body.style.overflow = '';
    }
</script>
