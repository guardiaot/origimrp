<?php
session_start();
require_once("conexao.php");

if ($entrada_sistema == 'Login') {
  // Verifica se o usuário está logado (exemplo: verifica se existe uma sessão)
  if (!isset($_SESSION['usuario_logado_pagina'])) {
    if ($entrada_sistema == 'Login') {
      echo '<script>window.location="login.php"</script>';
    }
  }
}

//buscar as informações
$query = $pdo->query("SELECT * FROM site");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$logo = @$res[0]['logo'];
$titulo = @$res[0]['titulo'];
$subtitulo = @$res[0]['subtitulo'];
$botao1 = @$res[0]['botao1'];
$botao2 = @$res[0]['botao2'];
$botao3 = @$res[0]['botao3'];
$item1 = @$res[0]['item1'];
$item2 = @$res[0]['item2'];
$item3 = @$res[0]['item3'];
$titulo_recursos = @$res[0]['titulo_recursos'];
$titulo_perguntas = @$res[0]['titulo_perguntas'];
$titulo_rodape = @$res[0]['titulo_rodape'];
$link_rodape = @$res[0]['link_rodape'];
$descricao_rodape = @$res[0]['descricao_rodape'];
$botao_rodape = @$res[0]['botao_rodape'];
$logo_topo = @$res[0]['logo_topo'];
$fundo_topo = @$res[0]['fundo_topo'];
$fundo_topo_mobile = @$res[0]['fundo_topo_mobile'];
$descricao_site = @$res[0]['descricao_site'];
$video_site = @$res[0]['video_site'];


$bg_desktop = "";
$bg_mobile = "";

if ($fundo_topo != "sem-foto.png") {
  $bg_desktop = $url_sistema . "img/logos/" . $fundo_topo;
}

if ($fundo_topo_mobile != "sem-foto.png") {
  $bg_mobile = $url_sistema . "img/logos/" . $fundo_topo_mobile;
}


if ($logo == "") {
  $logo = 'sem-foto.png';
}

// Separando o título em palavras
$palavras = @explode(' ', $titulo);
// Pegando as últimas duas palavras
$ultimo_palavra = array_pop($palavras);
$penultima_palavra = array_pop($palavras);

// Recriar o título com as últimas duas palavras com a classe 'text-accent'
$titulo_modificado = implode(' ', $palavras) . ' <span class="text-accent">' . $penultima_palavra . ' ' . $ultimo_palavra . '</span>';


if (@strpos($subtitulo, $nome_sistema) !== false) {
  // Se o texto foi encontrado, colocamos ele dentro da tag <strong>
  $subtitulo_modificado = @str_ireplace($nome_sistema, "<strong>$nome_sistema</strong>", $subtitulo);
} else {
  // Caso não tenha o texto, usamos o título original
  $subtitulo_modificado = $subtitulo;
}


if (@strpos($descricao_rodape, $nome_sistema) !== false) {
  // Se o texto foi encontrado, colocamos ele dentro da tag <strong>
  $descricao_rodape_modificado = @str_ireplace($nome_sistema, "<strong class='text-white'>$nome_sistema</strong>", $descricao_rodape);
} else {
  // Caso não tenha o texto, usamos o título original
  $descricao_rodape_modificado = $descricao_rodape;
}


?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="keywords" content="<?php echo $nome_sistema ?>, escritório de advocacia, advogados, etc" />
  <meta name="description" content="<?php echo $nome_sistema ?>" />
  <meta name="author" content="Origem" />
  <title><?php echo $nome_sistema ?></title>
  <!-- Favicon -->
  <link rel="icon" href="<?php echo $url_sistema ?>img/icone.png" type="image/x-icon" />
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="css/estilos_site.css">
  <!-- GSAP -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              DEFAULT: '#0F4C81',
              dark: '#0A3A62',
              light: '#1A6DB3'
            },
            secondary: {
              DEFAULT: '#1E293B',
              dark: '#0F172A',
              light: '#334155'
            },
            accent: {
              DEFAULT: '#38BDF8',
              dark: '#0284C7',
              light: '#7DD3FC'
            },
            success: {
              DEFAULT: '#10B981',
              dark: '#059669',
              light: '#34D399'
            },
            warning: {
              DEFAULT: '#F59E0B',
              dark: '#D97706',
              light: '#FBBF24'
            },
            danger: {
              DEFAULT: '#EF4444',
              dark: '#DC2626',
              light: '#F87171'
            },
            neutral: {
              50: '#F8FAFC',
              100: '#F1F5F9',
              200: '#E2E8F0',
              300: '#CBD5E1',
              400: '#94A3B8',
              500: '#64748B',
              600: '#475569',
              700: '#334155',
              800: '#1E293B',
              900: '#0F172A',
            }
          },
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
          },
          boxShadow: {
            'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
            'card-hover': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
          }
        }
      }
    }
  </script>



  <style>
    @media (max-width: 768px) {
      .fundo-topo {
        background-image: url('<?php echo $bg_mobile ?>') !important;
      }
    }
  </style>


</head>

<body class="font-poppins bg-gradient-to-br from-primary-dark to-primary text-white min-h-screen overflow-x-hidden">
  <div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="container mx-auto">

      <!-- Bloco CTA Simplificado -->
      <div class="mb-12 bg-primary-dark p-8 rounded-xl border border-accent/20 shadow-lg fundo-topo"
        style="background-image: url('<?php echo $bg_desktop ?>'); background-size: cover; background-position: center;">
        <div class="text-center mb-6">
          <!-- Logo adicionada acima do título -->
          <div class="flex justify-center mb-6">
            <?php if ($logo_topo != 'Não') { ?>
              <img src="<?php echo $url_sistema ?>img/logos/<?php echo $logo ?>" alt="Logo" />
            <?php } else {
              echo '<br>&nbsp;<br><br>&nbsp;<br><br>&nbsp;<br>';
            } ?>
          </div>

          <?php if ($titulo != "") { ?>
            <h1 class="text-3xl md:text-4xl font-bold mb-4">
              <?php echo $titulo_modificado ?>
            </h1>
          <?php } ?>

          <?php if ($subtitulo != "") { ?>
            <p class="text-gray-300 text-lg max-w-3xl mx-auto">
              <strong class="text-white"></strong> <?php echo $subtitulo_modificado ?>
            </p>
          <?php } ?>

        </div>

        <?php if ($botao1 != "" || $botao2 != "" || $botao3 != "") { ?>
          <div class="flex flex-col sm:flex-row justify-center gap-4 mb-6">
            <?php if ($botao1 != "") { ?>
              <a href="#faq-container"
                class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-lg text-white bg-green-500 hover:bg-green-700 focus:outline-none animate-pulse-green transition-all duration-300">
                <i class="fas fa-question mr-2"></i> <?php echo $botao1 ?>
              </a>
            <?php } ?>
            <?php if ($botao2 != "") { ?>
              <a href="#faq-produtos"
                class="inline-flex items-center justify-center px-6 py-3 border border-white/20 text-base font-medium rounded-lg shadow-lg text-white hover:bg-white/10 focus:outline-none transition-all duration-300">
                <i class="fas fa-box-open mr-2"></i> <?php echo $botao2 ?>
              </a>
            <?php } ?>
            <?php if ($botao3 != "") { ?>
              <a href="login.php"
                class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none animate-pulse-blue transition-all duration-300">
                <i class="fas fa-key mr-2"></i> <?php echo $botao3 ?>
              </a>
            <?php } ?>
          </div>
        <?php } ?>

        <?php if ($item1 != "" || $item2 != "" || $item3 != "") { ?>
          <div class="flex justify-center flex-wrap gap-4 text-sm text-gray-300">
            <div class="flex items-center">
              <i class="fas fa-check-circle text-accent mr-2"></i> <?php echo $item1 ?>
            </div>
            <div class="flex items-center">
              <i class="fas fa-check-circle text-accent mr-2"></i> <?php echo $item2 ?>
            </div>
            <div class="flex items-center">
              <i class="fas fa-check-circle text-accent mr-2"></i> <?php echo $item3 ?>
            </div>
          </div>
        <?php } ?>

        <br>&nbsp;<br>

      </div>








      <section class="bg-white py-10 px-4 md:px-10 rounded-xl border border-accent/20" id="area_sobre">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-<?=  $video_site != "" ? 2 : 1 ?> gap-10 items-center">

        <?php if ($video_site != "") { ?>
          <div class="w-full h-64 md:h-96 lg:h-[400px]">
            <iframe class="rounded-xl shadow-lg w-full h-full" src="<?php echo $video_site ?>"
              title="Vídeo de Apresentação" frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen></iframe>
          </div>
        <?php } ?>
          <!-- Texto + Botões -->
          <div>
            <h2 class="w-full text-2xl font-bold text-primary mb-4">Sobre Nós</h2>
            <p id="descricao" class="text-gray-700 mb-6" style="font-size: 14px"></p>

            <div class="flex flex-wrap gap-2">
              <?php if ($instagram_sistema != "") { ?>
                <a href="<?php echo $instagram_sistema ?>" target="_blank"
                  class="bg-pink-500 text-white text-xs px-2 py-1 rounded hover:bg-pink-600 transition-all">
                  <i class="fab fa-instagram mr-1"></i>Instagram
                </a>
              <?php } ?>


            </div>

          </div>
        </div>
      </section>





      <br>
      <?php
      $query = $pdo->query("SELECT * FROM recursos_site order by posicao_recurso asc");
      $res = $query->fetchAll(PDO::FETCH_ASSOC);
      $total_reg = @count($res);
      if ($total_reg > 0) {
        ?>

        <!-- Feature Cards Section -->
        <div class="mb-10 md:mb-20">
          <?php if ($titulo_recursos != "") { ?>
            <h2 class="text-3xl font-bold text-center mb-6 md:mb-10 gsap-fade-up"><?php echo $titulo_recursos ?></h2>
          <?php } ?>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8" id="features-container">

            <?php
            for ($i = 0; $i < $total_reg; $i++) {
              $id = $res[$i]['id'];
              $titulo = $res[$i]['titulo_recurso'];
              $icone = $res[$i]['icone_recurso'];
              $descricao = $res[$i]['descricao_recurso'];
              $posicao = $res[$i]['posicao_recurso'];

              ?>
              <div class="bg-glass rounded-xl p-6 text-center feature-card gsap-stagger-item">
                <div class="text-4xl mb-4 custom-green-icon" style="color: #2ba304;">
                  <i class="<?php echo $icone ?>"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3"><?php echo $titulo ?></h3>
                <p class="text-gray-300"><?php echo $descricao ?></p>
              </div>
            <?php } ?>


          </div>
        </div>

      <?php } ?>




      <?php
      $query = $pdo->query("SELECT * FROM usuarios WHERE nivel = 'Advogado' OR nivel = 'Advogada' ORDER BY id ASC");
      $res = $query->fetchAll(PDO::FETCH_ASSOC);
      $total_reg = @count($res);
      if ($total_reg > 0) {
        ?>

        <div class="container mx-auto p-4" id="faq-produtos">
          <!-- Título -->
          <h2 class="text-2xl sm:text-3xl font-bold text-white border-l-4 border-yellow-400 pl-4 mb-6">
            Conheça Nossos Advogados
          </h2>

          <!-- Cards menores -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php
            for ($i = 0; $i < $total_reg; $i++) {
              $nome_advogado = $res[$i]['nome'];
              $telefone_advogado = $res[$i]['telefone'];
              $telefone_advogadoF = '55' . preg_replace('/[ ()-]+/', '', $telefone_advogado);
              $especialidade = $res[$i]['especialidade'];
              $foto_advogado = $res[$i]['foto'];
              $caminho_foto = $foto_advogado != "" ? "painel/images/perfil/" . $foto_advogado : "sem-foto.jpg";
              ?>

              <div class="flex items-center bg-gray-800 rounded-lg shadow-md p-3 hover:shadow-lg transition">
                <img src="<?= $caminho_foto ?>" alt="Foto de <?= $nome_advogado ?>"
                  class="w-20 h-20 object-cover rounded-full border-2 border-gray-700 mr-4">
                <div>
                  <h3 class="text-white font-semibold text-lg"><?= $nome_advogado ?></h3>
                  <?php if ($especialidade != "") { ?>
                    <p class="text-gray-300 text-sm flex items-center mt-1">
                      <i class="bi bi-award-fill text-yellow-400 mr-2"></i> <?= $especialidade ?>
                    </p>
                  <?php } ?>
                  <p class="text-gray-400 text-sm flex items-center mt-1">
                    <a href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo @$telefone_advogadoF ?>"
                      target="_blank"><i class="bi bi-telephone-fill text-green-400 mr-2"></i> <?= $telefone_advogado ?></a>
                  </p>
                </div>
              </div>


            <?php } ?>
          </div>
        </div>

      <?php } ?>







      <br> <br>

      <?php
      $query = $pdo->query("SELECT * FROM perguntas_site order by posicao_pergunta asc");
      $res = $query->fetchAll(PDO::FETCH_ASSOC);
      $total_reg = @count($res);
      if ($total_reg > 0) {
        ?>

        <!-- FAQ Section -->
        <div class="mb-12 md:mb-20 rounded-xl border border-accent/20" style="background: #0e1e4a; padding:15px">
          <?php if ($titulo_perguntas != "") { ?>
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-6 md:mb-10 gsap-fade-up">
              <?php echo $titulo_perguntas ?></h2>
          <?php } ?>
          <div class="max-w-3xl mx-auto space-y-3 md:space-y-4 px-4" id="faq-container">

            <?php
            for ($i = 0; $i < $total_reg; $i++) {
              $id = $res[$i]['id'];
              $titulo = $res[$i]['titulo_pergunta'];
              $descricao = $res[$i]['descricao_pergunta'];
              $posicao = $res[$i]['posicao_pergunta'];

              if (@strpos($descricao, $nome_sistema) !== false) {
                // Se o texto foi encontrado, colocamos ele dentro da tag <strong>
                $descricao_modificado = @str_ireplace($nome_sistema, "<strong>$nome_sistema</strong>", $descricao);
              } else {
                // Caso não tenha o texto, usamos o título original
                $descricao_modificado = $descricao;
              }

              ?>

              <div class="bg-glass border border-gray-700 rounded-xl overflow-hidden gsap-stagger-item faq-item">
                <button class="w-full flex justify-between items-center p-4 text-left font-semibold focus:outline-none"
                  onclick="toggleFaq('faq<?php echo $id ?>')">
                  <?php echo $titulo ?>
                  <i class="fas fa-chevron-down transition-transform duration-300" id="icon-faq1"></i>
                </button>
                <div class="hidden p-4 bg-gray-900 bg-opacity-50 border-t border-gray-700" id="faq<?php echo $id ?>">
                  <?php echo $descricao_modificado ?>
                </div>
              </div>

            <?php } ?>


          </div>
        </div>

      <?php } ?>


      <?php if ($titulo_rodape != "" or $descricao_rodape != "" or $botao_rodape != "") { ?>
        <!-- CTA Final após FAQ -->
        <div class="mb-12 md:mb-20 bg-glass rounded-xl p-8 border border-accent/20 shadow-lg text-center"
          style="background:#010a1a">
          <?php if ($titulo_rodape != "") { ?>
            <h2 class="text-2xl md:text-3xl font-bold mb-4"><?php echo $titulo_rodape ?></h2>
          <?php } ?>

          <?php if ($descricao_rodape_modificado != "") { ?>
            <p class="text-gray-300 text-lg max-w-3xl mx-auto mb-8">
              <?php echo $descricao_rodape_modificado ?>
            </p>
          <?php } ?>

          <?php if ($botao_rodape != "") { ?>
            <div class="flex justify-center">
              <a href="<?php echo $link_rodape ?>"
                class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-lg text-white bg-blue-500 hover:bg-blue-700 focus:outline-none animate-pulse-green transition-all duration-300 transform hover:-translate-y-1">
                <i class="fas fa-rocket mr-2"></i> <?php echo $botao_rodape ?>
              </a>
            </div>
          <?php } ?>

        </div>
      <?php } ?>


      <!-- Footer -->
      <footer class="py-4 md:py-6 border-t border-gray-700 gsap-fade-up">
        <div class="container mx-auto flex flex-col md:flex-row items-center justify-center md:justify-between px-4">
          <!-- Logo Grande no Footer - ajustado para ser menor em dispositivos móveis -->
          <div class="flex justify-center mb-4 md:mb-6">
            <img src="<?php echo $url_sistema ?>img/logos/<?php echo $logo ?>" alt="Logo"
              class="large-logo-footer h-16 md:h-20" />
          </div>
          <div class="text-center md:text-right">
            <p class="text-gray-100 text-sm">&copy; <?php echo date('Y'); ?> <?php echo $nome_sistema ?></p>
            <p class="text-gray-100 text-xs md:text-sm mt-1 md:mt-2"><?php echo $telefone_sistema ?> –
              <?php echo $endereco_sistema ?></p>
          </div>
        </div>
      </footer>
    </div>
  </div>



  <a title="Ir para o whatsapp" target="_blank"
    href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo @$tel_whats ?>"><img
      src="<?php echo $url_sistema ?>img/logo_whats.png" width="50px"
      style="position:fixed; bottom:20px; right:20px; z-index: 100"></a>


  <script src="assets/js/lgpd-cookie.js" type="module"></script>
  <Lgpd-cookie
    text='Não capturamos nenhuma informação de dados sensíveis, somente cookies para melhor performance de nosso website!' />

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- JavaScript for FAQ Accordion and Responsive Utilities -->
  <script>
    // FAQ Accordion
    function toggleFaq(id) {
      const content = document.getElementById(id);
      const icon = document.getElementById('icon-' + id);
      if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.classList.add('rotate-180');
      } else {
        content.classList.add('hidden');
        icon.classList.remove('rotate-180');
      }
    }
    // Utilitários responsivos
    function adjustForScreenSize() {
      const isMobile = window.innerWidth < 768;
      const isSmallMobile = window.innerWidth < 480;
      // Ajustar tamanho do logo
      const logos = document.querySelectorAll('.large-logo');
      logos.forEach(logo => {
        if (isSmallMobile) {
          logo.style.height = '70px';
        } else if (isMobile) {
          logo.style.height = '90px';
        } else {
          logo.style.height = '120px';
        }
      });
      // Ajustar tamanho do logo no footer
      const footerLogos = document.querySelectorAll('.large-logo-footer');
      footerLogos.forEach(logo => {
        if (isSmallMobile) {
          logo.style.height = '50px';
        } else if (isMobile) {
          logo.style.height = '60px';
        } else {
          logo.style.height = '80px';
        }
      });
      // Ajustar padding em cards
      const cards = document.querySelectorAll('.feature-card, .plan-card .bg-glass');
      cards.forEach(card => {
        if (isMobile) {
          card.style.padding = isSmallMobile ? '0.75rem' : '1rem';
        }
      });
    }
    // Melhorar o scroll em dispositivos móveis
    function smoothScrollToElement(elementId) {
      const element = document.getElementById(elementId);
      if (element) {
        const headerOffset = 60;
        const elementPosition = element.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });
      }
    }
    // GSAP Animations
    document.addEventListener('DOMContentLoaded', function () {
      // Executar ajustes responsivos
      adjustForScreenSize();
      // Adicionar listener para redimensionamento
      window.addEventListener('resize', adjustForScreenSize);
      // Substituir links de âncora por scroll suave
      const anchorLinks = document.querySelectorAll('a[href^="#"]');
      anchorLinks.forEach(link => {
        link.addEventListener('click', function (e) {
          e.preventDefault();
          const targetId = this.getAttribute('href').substring(1);
          smoothScrollToElement(targetId);
        });
      });
      // Registrar o plugin ScrollTrigger
      gsap.registerPlugin(ScrollTrigger);
      // Animação dos títulos de seção
      gsap.utils.toArray(".gsap-fade-up").forEach(element => {
        gsap.to(element, {
          scrollTrigger: {
            trigger: element,
            start: "top 80%",
          },
          opacity: 1,
          y: 0,
          duration: 0.8,
          ease: "power2.out"
        });
      });
      // Animação dos cards de planos
      gsap.to(".plan-card", {
        scrollTrigger: {
          trigger: "#plans-container",
          start: "top 70%",
        },
        opacity: 1,
        y: 0,
        stagger: 0.1,
        duration: 0.6,
        ease: "back.out(1.2)"
      });
      // Animação dos preços
      gsap.from(".price-text", {
        scrollTrigger: {
          trigger: "#plans-container",
          start: "top 60%",
        },
        textContent: 0,
        duration: 1.5,
        ease: "power1.out",
        snap: { textContent: 1 },
        stagger: 0.1,
        delay: 0.5
      });
      // Animação dos itens de recursos
      gsap.from(".feature-item", {
        scrollTrigger: {
          trigger: "#plans-container",
          start: "top 50%",
        },
        opacity: 0,
        x: -20,
        stagger: 0.05,
        duration: 0.4,
        delay: 0.8
      });
      // Animação dos cards de recursos
      gsap.to(".feature-card", {
        scrollTrigger: {
          trigger: "#features-container",
          start: "top 70%",
        },
        opacity: 1,
        y: 0,
        stagger: 0.1,
        duration: 0.6,
        ease: "back.out(1.2)"
      });
      // Animação dos itens de FAQ
      gsap.to(".faq-item", {
        scrollTrigger: {
          trigger: "#faq-container",
          start: "top 80%",
        },
        opacity: 1,
        y: 0,
        stagger: 0.1,
        duration: 0.6
      });
      // Efeito de hover nos botões de assinatura
      const buttons = document.querySelectorAll('.btn-subscribe');
      buttons.forEach(button => {
        button.addEventListener('mouseenter', () => {
          gsap.to(button, {
            scale: 1.05,
            duration: 0.3,
            ease: "power1.out"
          });
        });
        button.addEventListener('mouseleave', () => {
          gsap.to(button, {
            scale: 1,
            duration: 0.3,
            ease: "power1.out"
          });
        });
      });
      // Efeito de parallax no fundo
      gsap.to("body", {
        backgroundPosition: "50% 100%",
        ease: "none",
        scrollTrigger: {
          trigger: "body",
          start: "top top",
          end: "bottom top",
          scrub: true
        }
      });
    });
  </script>
</body>

</html>





<script>
  document.addEventListener('DOMContentLoaded', () => {
    const inner = document.getElementById('carousel-inner');
    const slides = inner.children;
    const total = slides.length;
    let idx = 0;
    const interval = 5000;
    let timer;

    function show(i) {
      inner.style.transform = `translateX(-${i * 100}%)`;
      bullets.forEach((b, j) => b.classList.toggle('bg-white', j === i));
    }

    function next() { idx = (idx + 1) % total; show(idx); }
    function prev() { idx = (idx - 1 + total) % total; show(idx); }

    // reset timer
    function reset() {
      clearInterval(timer);
      timer = setInterval(next, interval);
    }

    // botões
    document.getElementById('nextSlide').addEventListener('click', () => { next(); reset(); });
    document.getElementById('prevSlide').addEventListener('click', () => { prev(); reset(); });

    // bullets
    const bullets = Array.from(document.querySelectorAll('.carousel-bullet'));
    bullets.forEach(b => {
      b.addEventListener('click', () => {
        idx = parseInt(b.dataset.index);
        show(idx);
        reset();
      });
    });

    // inicia
    show(0);
    timer = setInterval(next, interval);
  });
</script>


<script type="text/javascript">
  // Passa o conteúdo da variável PHP para uma variável JavaScript
  var descricaoSite = <?php echo json_encode($descricao_site); ?>;

  // Usando o conteúdo na página
  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('descricao').innerHTML = descricaoSite;
  });
</script>