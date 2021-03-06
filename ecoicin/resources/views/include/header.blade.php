  <div class="page-content">
      <div>
          <div class="header-top">
              <header>
                <div class="top-head ml-lg-auto text-center">
                  <div class="row">
                    <div class="col-md-6 float-left">

                      <a class="text-light">
                        <i class="fas fa-user"></i> {{saludarUsuario()}}</a>
                    </div>
                    <div class="col-md-6 float-right">
                      @guest
                        <a href="#" data-toggle="modal" data-target="#exampleModalCenter">
                          <i class="fas fa-lock"></i> Iniciar sesión</a>

                      @endguest
                      @auth
                        <a href="{{route('logout')}}">
                            <i class="fas fa-lock"></i> Cerrar sesión</a>
                      @endauth
                    </div>
                  </div>
                </div>
                  <div class="clearfix"></div>

                  @include('include/navbar')
              </header>
          </div>
      </div>
  </div>
