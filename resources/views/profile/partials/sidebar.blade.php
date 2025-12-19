<!-- ============================= -->
<!--   SIDEBAR + STYLE PORNHUB     -->
<!-- ============================= -->

<style>
    /* Sidebar background */
    #layout-menu {
        background-color: #000 !important;
        color: #fff !important;
        padding-top: 15px;
        border-right: 2px solid #ffa31a;
    }

    /* Logo + Brand */
    .app-brand-text {
        color: #ffa31a !important;
        font-size: 1.3rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    /* Menu item */
    .menu-item a.menu-link {
        color: #ddd !important;
        font-weight: 600;
        padding: 12px 18px;
        border-radius: 6px;
    }

    /* Icon color */
    .menu-item .menu-icon {
        color: #ffa31a !important;
        font-size: 20px;
    }

    /* Hover effect */
    .menu-item a.menu-link:hover {
        background-color: #111 !important;
        color: #ffa31a !important;
        transition: 0.2s;
    }

    /* Active menu */
    .menu-item.active a.menu-link {
        background-color: #ffa31a !important;
        color: #000 !important;
        font-weight: 700;
    }

    .menu-item.active .menu-icon {
        color: #000 !important;
    }

    /* Menu header */
    .menu-header-text {
        color: #ffa31a !important;
        font-weight: 700;
        font-size: 0.85rem;
    }
      .ph-logo {
        display: flex;
        align-items: center;
        font-weight: 900;
        font-size: 1.6rem;
        font-family: Arial, Helvetica, sans-serif;
    }

    .ph-logo .ph-white {
        color: #ffffff;
        letter-spacing: 0.5px;
    }

    .ph-logo .ph-orange {
        background-color: #ffa31a;
        color: #000;
        padding: 2px 10px;
        border-radius: 6px;
        margin-left: 4px;
        font-weight: 900;
    }
</style>


<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <div class="app-brand demo" style="padding-bottom: 20px;">
    <a href="{{ route('home') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <!-- Logo SVG asli tetap pakai punyamu -->
        <!-- Bisa ditempatkan di sini seperti sebelumnya -->
      </span>
      <span class="app-brand-text demo menu-text fw-bolder ms-2">
    <span class="ph-logo">
        <span class="ph-white">Project</span>
        <span class="ph-orange">Ujian</span>
    </span>
</span>
    </a>

    <a href="javascript:void(0);" 
       class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle" style="color:#ffa31a;"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">

    <!-- Dashboard -->
    <li class="menu-item active">
      <a href="{{ route('home')}}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div>Dashboard</div>
      </a>
    </li>

    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Forms &amp; Tables</span>
    </li>

    <!-- Barangs -->
    {{-- <li class="menu-item">
      <a href="{{ route('barangs.index')}}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-box"></i>
        <div>Barangs</div>
      </a>
    </li>

    <!-- Pembeli -->
    <li class="menu-item">
      <a href="{{ route('pembelis.index')}}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div>Pembeli</div>
      </a>
    </li>

    <!-- Supplier -->
    <li class="menu-item">
      <a href="{{ route('suplliers.index')}}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div>Supplier</div>
      </a>
    </li>

    <!-- Pesanan -->
    <li class="menu-item">
      <a href="{{ route('pesanans.index')}}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
        <div>Pesanan</div>
      </a>
    </li>

    <!-- Pembelians -->
    <li class="menu-item">
      <a href="{{ route('pembelians.index')}}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
        <div>Pembelians</div>
      </a>
    </li> --}}

  </ul>

</aside>
