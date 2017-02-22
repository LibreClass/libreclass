<div class="container-fluid topo navbar-fixed-top">
  <div class="container">

    <nav class="menu" role="navigation">

      <div class="menu-icon visible-xs visible-sm">
        <i class="fa fa-navicon fa-2x fa-fw"></i>
      </div>
      <div class="menu-logo text-center visible-md visible-lg">
        {{ HTML::image("images/logo-libreclass-vertical.png", "Logomarca Libreclass", ["class" => "logomarca"]) }}
      </div>
      <div class="menu-list visible-lg visible-md">
        <ul class="list-inline text-right scroll">
          <li>
            <a href="#home">Inicio</a>
          </li>
          <li>
            <a href="#features">Funcionalidades</a>
          </li>
          <!-- <li>
            <a href="#plans">Planos</a>
          </li> -->
          <li>
            <div class="dropdown text-left text-sm">
              <a class="dropdown-toggle click" id="dropdownDownload" data-toggle="dropdown" aria-expanded="true">
                Download
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownDownload">
                <li role="presentation">
                  <a href="ftp://anonymous@sysvale.com:contato@sysvale.com@ftp.sysvale.com/LibreClass_x86.exe" target="_blank">
                    <i class="fa fa-windows text-blue"></i>
                    Libreclass Offline Win
                  </a>
                </li>
                <li role="presentation">
                  <a href="ftp://anonymous@sysvale.com:contato@sysvale.com@ftp.sysvale.com/libreclass_1.0_i386.deb" target="_blank">
                    <i class="fa fa-linux text-blue"></i>
                    Libreclass Offline Linux32
                  </a>
                </li>
                <li role="presentation">
                  <a href="ftp://anonymous@sysvale.com:contato@sysvale.com@ftp.sysvale.com/libreclass_1.0_amd64.deb" target="_blank">
                    <i class="fa fa-linux text-blue"></i>
                    Libreclass Offline Linux64
                  </a>
                </li>
              </ul>
            </div>
          </li>
          <li>
            <!--<button  type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalLogin"><b>Login</b></button>-->
            <a href="/login" style="color: #fff" class="btn btn-primary btn-lg">Login</a>
          </li>
        </ul>
      </div>
      <div class="menu-list text-right visible-xs visible-sm">
        <a href="/login" style="color: #fff" class="btn btn-primary btn-lg">Login</a>
      </div>

    </nav>
  </div>
</div>
<nav class="menu-slider visible-sm visible-xs">
  <div class="row">
    <div class="col-xs-12">
      {{ HTML::image("images/logo-libreclass-vertical.png", "Logomarca Libreclass", ["class" => "logomarca img-responsive"]) }}
    </div>
  </div>
  <ul class="list-unstyled scroll">
    <li><a href="#home">Inicio</a></li>
    <li><a href="#features">Funcionalidades</a></li>
    <li><a href="#plans">Planos</a></li>
  </ul>
</nav>
