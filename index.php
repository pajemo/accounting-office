<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MKM Biuro Rachunkowe - Profesjonalne Usługi Księgowe w Nysie</title>
  
  <!-- SEO Meta Tags -->
  <meta name="description" content="MKM Biuro Rachunkowe w Nysie - profesjonalne usługi księgowe, rejestracja działalności, księga podatkowa, kadry i płace. 25+ lat doświadczenia. Zaufaj ekspertom!" />
  <meta name="keywords" content="biuro rachunkowe nysa, MKM księgowość, usługi księgowe nysa, rejestracja działalności nysa, księga podatkowa, kadry płace nysa, księgowy nysa" />
  <meta name="author" content="MKM Biuro Rachunkowe" />
  <meta name="robots" content="index, follow" />
  
  <!-- Canonical URL -->
  <link rel="canonical" href="https://biurorachunkowenysa.com/" />
  
  <!-- Open Graph Tags for Social Media -->
  <meta property="og:title" content="MKM Biuro Rachunkowe - Profesjonalne Usługi Księgowe w Nysie" />
  <meta property="og:description" content="Profesjonalne usługi księgowe w Nysie. 25+ lat doświadczenia w księgowości, rejestracji działalności i kadrach." />
  <meta property="og:url" content="https://biurorachunkowenysa.com/" />
  <meta property="og:type" content="website" />
  <meta property="og:locale" content="pl_PL" />
  <meta property="og:image" content="https://biurorachunkowenysa.com/image/mkm-logo.png" />
  <meta property="og:image:alt" content="MKM Biuro Rachunkowe Logo" />
  
  <!-- Organization Schema with Logo for Google Search -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "MKM Biuro Rachunkowe",
    "url": "https://biurorachunkowenysa.com",
    "logo": "https://biurorachunkowenysa.com/image/mkm-logo.png",
    "image": "https://biurorachunkowenysa.com/image/mkm-logo.png",
    "description": "Profesjonalne usługi księgowe w Nysie - 25+ lat doświadczenia",
    "telephone": "+48664767930",
    "email": "malgorzatakrzyzowska@wp.pl",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "ul. Grunwaldzka 1",
      "addressLocality": "Nysa",
      "addressRegion": "Opolskie",
      "postalCode": "48-300",
      "addressCountry": "PL"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": 50.4743,
      "longitude": 17.3344
    },
    "foundingDate": "1999",
    "areaServed": {
      "@type": "City",
      "name": "Nysa"
    },
    "sameAs": [
      "https://biurorachunkowenysa.com"
    ]
  }
  </script>
  
  <!-- Local Business Schema -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "AccountingService",
    "name": "MKM Biuro Rachunkowe",
    "description": "Profesjonalne usługi księgowe w Nysie",
    "url": "https://biurorachunkowenysa.com",
    "logo": "https://biurorachunkowenysa.com/image/mkm-logo.png",
    "telephone": "+48664767930",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "ul. Grunwaldzka 1",
      "addressLocality": "Nysa",
      "addressRegion": "Opolskie", 
      "postalCode": "48-300",
      "addressCountry": "PL"
    },
    "serviceArea": "Nysa",
    "priceRange": "$$"
  }
  </script>
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="image/favicon.png?v=1">
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
  <link rel="manifest" href="/site.webmanifest">
  <!-- Critical CSS for Hero Section Only - Fast Loading -->
  <style>
  /* Hero Section Critical Styles - Inline for Instant Display */
  .hero-carousel {
    height: 70vh;
    min-height: 500px;
    max-height: 700px;
    position: relative;
    overflow: hidden;
    background: #b10a21;
  }
  
  .carousel-container {
    position: relative;
    width: 100%;
    height: 100%;
  }
  
  .carousel-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .carousel-slide.active {
    opacity: 1;
  }
  
  .slide-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
  }
  
  .slide-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    z-index: 1;
  }
  
  .slide-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
    max-width: 900px;
    padding: 0 30px;
  }
  
  .slide-content h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
    line-height: 1.2;
  }
  
  .slide-content p {
    font-size: 1.3rem;
    margin-bottom: 2.5rem;
    opacity: 0.95;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.6);
    line-height: 1.5;
  }
  
  .hero-buttons {
    display: flex;
    gap: 1.5rem;
    justify-content: center;
    flex-wrap: wrap;
  }
  
  .btn-primary, .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    border: 2px solid transparent;
  }
  
  .btn-primary {
    background: white;
    color: #b10a21;
    border-color: white;
  }
  
  .btn-secondary {
    background: transparent;
    color: white;
    border-color: white;
  }
  
  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
  }
  
  .btn-secondary:hover {
    background: white;
    color: #b10a21;
  }
  
  /* Hero Mobile Optimization */
  @media (max-width: 768px) {
    .hero-carousel {
      height: 50vh;
      min-height: 400px;
    }
    
    .slide-content h1 {
      font-size: 2.5rem;
    }
    
    .slide-content p {
      font-size: 1.1rem;
    }
    
    .hero-buttons {
      flex-direction: column;
      align-items: center;
      gap: 1rem;
    }
    
    .btn-primary, .btn-secondary {
      width: 80%;
      max-width: 300px;
      justify-content: center;
    }
  }
  
  @media (max-width: 480px) {
    .hero-carousel {
      height: 45vh;
      min-height: 350px;
    }
    
    .slide-content h1 {
      font-size: 2rem;
    }
    
    .slide-content p {
      font-size: 1rem;
    }
    
    .slide-content {
      padding: 0 20px;
    }
  }
  </style>
  
  <!-- Regular CSS files load normally after hero is displayed -->
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="mobile-responsive.css">
  <link rel="stylesheet" href="modern-header.css">
  <link rel="stylesheet" href="homepage-modern.css">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<?php include 'header.html'; ?>

<!-- Hero Carousel Section -->
<section class="hero-carousel">
  <div class="carousel-container">
    <div class="carousel-slide active">
      <div class="slide-overlay"></div>
      <div class="slide-background" style="background-image: url('image/hero-accounting.jpg'), linear-gradient(135deg, #b10a21, #d41439);"></div>
      <div class="slide-content">
        <h1>Profesjonalne Usługi Księgowe</h1>
        <p>Zaufane doświadczenie w zarządzaniu finansami dla sukcesu Twojej firmy</p>
        <div class="hero-buttons">
          <a href="Contact.html" class="btn-primary">Zacznij Teraz</a>
          <a href="aboutus.html" class="btn-secondary">Dowiedz Się Więcej</a>
        </div>
      </div>
    </div>
    
    <div class="carousel-slide">
      <div class="slide-overlay"></div>
      <div class="slide-background" style="background-image: url('image/hero-excellence.jpg'), linear-gradient(135deg, #333, #555);"></div>
      <div class="slide-content">
        <h1>25+ lat Doskonałości</h1>
        <p>Wspieramy firmy kompleksowymi rozwiązaniami księgowymi od 2000 roku</p>
        <div class="hero-buttons">
          <a href="Business.html" class="btn-primary">Nasze Usługi</a>
          <a href="Contact.html" class="btn-secondary">Skontaktuj się z Nami</a>
        </div>
      </div>
    </div>
    
    <div class="carousel-slide">
      <div class="slide-overlay"></div>
      <div class="slide-background" style="background-image: url('image/hero-security.jpg'), linear-gradient(135deg, #b10a21, #333);"></div>
      <div class="slide-content">
        <h1>Twoje Bezpieczeństwo Finansowe</h1>
        <p>Kompleksowe ubezpieczenie i profesjonalna ochrona odpowiedzialności cywilnej</p>
        <div class="hero-buttons">
          <a href="Contact.html" class="btn-primary">Zaplanuj Konsultację</a>
          <a href="Business.html" class="btn-secondary">Zobacz Usługi</a>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Carousel Navigation -->
  <button class="carousel-btn prev" onclick="changeSlide(-1)">
    <i class="fas fa-chevron-left"></i>
  </button>
  <button class="carousel-btn next" onclick="changeSlide(1)">
    <i class="fas fa-chevron-right"></i>
  </button>
  
  <!-- Carousel Indicators -->
  <div class="carousel-indicators">
    <span class="indicator active" onclick="currentSlide(1)"></span>
    <span class="indicator" onclick="currentSlide(2)"></span>
    <span class="indicator" onclick="currentSlide(3)"></span>
  </div>
</section>

<!-- Quick Stats Section -->
<section class="stats-section">
  <div class="stats-container">
    <div class="stat-item">
      <div class="stat-icon">
        <i class="fas fa-calendar-alt"></i>
      </div>
      <div class="stat-number" data-target="25">0</div>
      <div class="stat-label">Lat Doświadczenia</div>
    </div>
    <div class="stat-item">
      <div class="stat-icon">
        <i class="fas fa-users"></i>
      </div>
      <div class="stat-number" data-target="100">0</div>
      <div class="stat-label">Zadowolonych Klientów</div>
    </div>
    <div class="stat-item">
      <div class="stat-icon">
        <i class="fas fa-shield-alt"></i>
      </div>
      <div class="stat-number" data-target="100">0</div>
      <div class="stat-label">Współczynnik Sukcesu %</div>
    </div>
    <div class="stat-item">
      <div class="stat-icon">
        <i class="fas fa-building"></i>
      </div>
      <div class="stat-number" data-target="5">0</div>
      <div class="stat-label">Kategorie Usług</div>
    </div>
  </div>
</section>

<!-- Services Overview Section -->
<section class="services-overview">
  <div class="container">
    <div class="section-header">
      <h2>Nasze Profesjonalne Usługi</h2>
      <p>Kompleksowe rozwiązania księgowe dostosowane do potrzeb Twojej firmy</p>
    </div>
    
    <div class="services-grid">
      <div class="service-card">
        <div class="service-icon">
          <i class="fas fa-registered"></i>
        </div>
        <h3>Rejestracja Działalności</h3>
        <p>Kompleksowe usługi zakładania i rejestracji firm dla nowych przedsiębiorców</p>
        <a href="Business.html" class="service-link">Dowiedz Się Więcej</a>
      </div>
      
      <div class="service-card">
        <div class="service-icon">
          <i class="fas fa-book-open"></i>
        </div>
        <h3>Księga Podatkowa</h3>
        <p>Profesjonalne prowadzenie ksiąg podatkowych przychodów i kosztów</p>
        <a href="expenses.html" class="service-link">Dowiedz Się Więcej</a>
      </div>
      
      <div class="service-card">
        <div class="service-icon">
          <i class="fas fa-chart-line"></i>
        </div>
        <h3>Księgowość Handlowa</h3>
        <p>Kompleksowe prowadzenie ksiąg handlowych i raportowanie finansowe</p>
        <a href="Book.html" class="service-link">Dowiedz Się Więcej</a>
      </div>
      
      <div class="service-card">
        <div class="service-icon">
          <i class="fas fa-calculator"></i>
        </div>
        <h3>Ryczałt Podatkowy</h3>
        <p>Uproszczone rozwiązania podatkowe z zarządzaniem ryczałtem od przychodów</p>
        <a href="tax.html" class="service-link">Dowiedz Się Więcej</a>
      </div>
      
      <div class="service-card">
        <div class="service-icon">
          <i class="fas fa-users-cog"></i>
        </div>
        <h3>Kadry i Płace</h3>
        <p>Kompleksowe usługi zarządzania zasobami ludzkimi i płacami</p>
        <a href="payroll.html" class="service-link">Dowiedz Się Więcej</a>
      </div>
      
      <div class="service-card">
        <div class="service-icon">
          <i class="fas fa-handshake"></i>
        </div>
        <h3>Doradztwo Biznesowe</h3>
        <p>Eksperckie porady finansowe i strategiczne doradztwo biznesowe</p>
        <a href="Contact.html" class="service-link">Dowiedz Się Więcej</a>
      </div>
    </div>
  </div>
</section>

<!-- Why Choose Us Section -->
<section class="why-choose-us">
  <div class="container">
    <div class="content-grid">
      <div class="content-image">
        <div class="image-container">
          <img src="image/professional-accountant.jpeg" alt="Profesjonalne Usługi Księgowe">
          <div class="image-overlay">
            <div class="overlay-content">
              <h3>Małgorzata Krzyżowska</h3>
              <p>Właścicielka i Główna Księgowa</p>
              <p>25+ lat Profesjonalnego Doświadczenia</p>
            </div>
          </div>
        </div>
      </div>
      
      <div class="content-text">
        <h2>Dlaczego Wybrać Biuro Rachunkowe MKM?</h2>
        <div class="feature-list">
          <div class="feature-item">
            <div class="feature-icon">
              <i class="fas fa-award"></i>
            </div>
            <div class="feature-content">
              <h4>Profesjonalna Doskonałość</h4>
              <p>Ekspercka wiedza absolwenta Uniwersytetu Opolskiego ze specjalizacją w rachunkowości i finansach</p>
            </div>
          </div>
          
          <div class="feature-item">
            <div class="feature-icon">
              <i class="fas fa-clock"></i>
            </div>
            <div class="feature-content">
              <h4>Zawsze Dostępni</h4>
              <p>Stały kontakt z klientami dzięki elastycznym godzinom pracy i zawsze dostępnemu telefonowi głównemu</p>
            </div>
          </div>
          
          <div class="feature-item">
            <div class="feature-icon">
              <i class="fas fa-globe"></i>
            </div>
            <div class="feature-content">
              <h4>Doświadczenie Międzynarodowe</h4>
              <p>Obsługujemy firmy zarówno z polskim, jak i zagranicznym kapitałem</p>
            </div>
          </div>
          
          <div class="feature-item">
            <div class="feature-icon">
              <i class="fas fa-shield-check"></i>
            </div>
            <div class="feature-content">
              <h4>Pełne Ubezpieczenie</h4>
              <p>Kompleksowe ubezpieczenie odpowiedzialności cywilnej dla Twojego spokoju ducha i bezpieczeństwa</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Client Types Section -->
<section class="client-types">
  <div class="container">
    <div class="section-header">
      <h2>Obsługujemy Wszystkie Typy Firm</h2>
      <p>Dostosowane rozwiązania księgowe dla każdej struktury biznesowej</p>
    </div>
    
    <div class="client-grid">
      <div class="client-card">
        <div class="client-icon">
          <i class="fas fa-building"></i>
        </div>
        <h3>Spółki Prawa Handlowego</h3>
        <p>Kompleksowa księgowość dla korporacji i spółek z ograniczoną odpowiedzialnością</p>
      </div>
      
      <div class="client-card">
        <div class="client-icon">
          <i class="fas fa-heart"></i>
        </div>
        <h3>Fundacje i Stowarzyszenia</h3>
        <p>Specjalistyczna księgowość dla organizacji non-profit i fundacji</p>
      </div>
      
      <div class="client-card">
        <div class="client-icon">
          <i class="fas fa-user-tie"></i>
        </div>
        <h3>Przedsiębiorcy Indywidualni</h3>
        <p>Spersonalizowane usługi księgowe dla właścicieli jednoosobowych firm i freelancerów</p>
      </div>
      
      <div class="client-card">
        <div class="client-icon">
          <i class="fas fa-globe-americas"></i>
        </div>
        <h3>Firmy Międzynarodowe</h3>
        <p>Ekspercka obsługa firm z kapitałem zagranicznym i działalnością międzynarodową</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
  <div class="cta-overlay"></div>
  <div class="container">
    <div class="cta-content">
      <h2>Gotowy na Transformację Finansów Twojej Firmy?</h2>
      <p>Dołącz do ponad 100+ zadowolonych klientów, którzy powierzają Biuru Rachunkowemu MKM swój sukces finansowy. Uzyskaj profesjonalne usługi księgowe, które pomogą Twojej firmie się rozwijać.</p>
      <div class="cta-buttons">
        <a href="Contact.html" class="btn-primary large">
          <i class="fas fa-phone"></i>
          Zaplanuj Konsultację
        </a>
        <a href="Business.html" class="btn-secondary large">
          <i class="fas fa-list"></i>
          Zobacz Wszystkie Usługi
        </a>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.html'; ?>

<!-- Scripts -->
<script type="text/javascript">
  function googleTranslateElementInit() {
    new google.translate.TranslateElement(
      { pageLanguage: 'en', includedLanguages: 'en,pl', autoDisplay: false },
      'google_translate_element'
    );
  }
</script>

<script type="text/javascript" 
  src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
</script>

<script src="mobile-navigation.js"></script>
<script src="homepage-carousel.js"></script>

</body>
</html>