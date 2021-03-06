<!-- Sidebar -->
<nav id="sidebar" class="sidebar-wrapper" >
    <div class="sidebar-content">
      <div class="sidebar-brand" style="background-color:#F5F5F5; border:solid black 1px;">
        <a href="/" class="text-center">
          <img src="/img/icon.png" height="100" width="100">
        </a>
        <div id="close-sidebar">
          <i class="far fa-arrow-alt-circle-left"></i>
        </div>
      </div>

      @php
          $name = \Illuminate\Support\Facades\Route::currentRouteName();
              /** @var \App\Models\User $user */
              $user = \Illuminate\Support\Facades\Auth::user();
      @endphp

      <div class="sidebar-menu">
        <ul>

          <li class="{{ $name == 'home.index' ? 'active' : '' }}">
            <a href="{{ route('home.index') }}"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
          </li>

          <li class="header-menu">
            <span>Sales & Operation</span>
          </li>

          @can('orders-access')
          <li class="{{ $name == 'order.index' ? 'active' : '' }}">
              <a href="{{ route('order.index', ['type' => 'customer']) }}"><i class="fas fa-cart-plus"></i>Orders</a>
          </li>

          <li class="{{ $name == 'order.index' ? 'active' : '' }}">
            <a href="{{ route('order.index', ['type' => 'agent']) }}"><i class="fas fa-cart-plus"></i>Orders (Agent)</a>
          </li>
          @endcan

          @can('transactions-access')
          <li class="{{ $name == 'transaction.index' ? 'active' : '' }}">
              <a href="{{ route('transaction.index') }}"><i class="far fa-credit-card"></i>Transactions</a>
          </li>
          @endcan

          @can('job-tickets-access')
          <li class="{{ $name == 'job-ticket.index' ? 'active' : '' }}">
              <a href="{{ route('job-ticket.index') }}"><i class="far fa-list-alt"></i>Job Tickets</a>
          </li>
          @endcan

          @can('user-management-access')
          <li class="header-menu">
            <span>Profile Management</span>
          </li>

          <li class="{{ $name == 'customer.index' ? 'active' : '' }}">
              <a href="{{ route('customer.index') }}"><i class="fas fa-users"></i>Customers</a>
          </li>
{{--
          <li class="{{ $name == 'member.index' ? 'active' : '' }}">
            <a href="{{ route('member.index') }}"><i class="fas fa-address-card"></i>Members</a>
          </li> --}}
          @role('admin|superadmin')
          <li class="{{ $name == 'admin.index' ? 'active' : '' }}">
            <a href="{{ route('admin.index') }}"><i class="fas fa-users-cog"></i>Admin</a>
          </li>
          @endrole
          @endcan

          <li class="header-menu">
            <span>Reporting</span>
          </li>

          <li class="header-menu">
            <span>Setting</span>
          </li>
          @can('setting-profile-access')
          <li class="{{ $name == 'profile.index' ? 'active' : '' }}">
            <a href="{{ route('profile.index') }}"><i class="far fa-user-circle"></i>Profile</a>
          </li>
          @endcan

          @can('setting-product-binding-access')
          <li class="{{ $name == 'product.index' ? 'active' : '' }}">
            <a href="{{ route('product.index') }}"><i class="fas fa-layer-group"></i>Product Binding</a>
          </li>
          @endcan

          @can('setting-pricing-access')
          <li class="{{ $name == 'price.index' ? 'active' : '' }}">
            <a href="{{ route('price.index', ['type' => 'customer']) }}"><i class="fas fa-tags"></i>Pricing</a>
          </li>

          <li class="{{ $name == 'price.index' ? 'active' : '' }}">
            <a href="{{ route('price.index', ['type' => 'agent']) }}"><i class="fas fa-tags"></i>Pricing (Agent)</a>
          </li>
          @endcan

          @can('setting-transaction-access')
          <li class="{{ $name == 'transaction.data' ? 'active' : '' }}">
            <a href="{{ route('transaction.data') }}"><i class="fas fa-database"></i>Transaction</a>
          </li>
          @endcan

          <li class="{{ $name == 'voucher.index' ? 'active' : '' }}">
            <a href="{{ route('voucher.index') }}"><i class="fas fa-ticket-alt"></i>Voucher</a>
          </li>

          @can('self-account-access')
          <li class="header-menu">
            <span>Self Setting</span>
          </li>

          <li class="{{ $name == 'user.account.index' ? 'active' : '' }}">
            <a href="{{ route('user.account.index') }}">
              <i class="fas fa-user-circle"></i>
              User Account
            </a>
          </li>
          @endcan

        </ul>
      </div>
    </div>
  </nav>
