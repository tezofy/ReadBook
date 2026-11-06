/* ==========================================================================
   NOCTURNE — CLIENT EDITION · DATA
   The catalogue, reviews and journal entries, exactly as they appear in the
   client's approved artefact. Kept in one place so the theme, the preview
   and the WooCommerce importer all read from the same source.

   Prices are in LEI, matching the artefact.
   ========================================================================== */
(function (global) {
  'use strict';

  var CLIENT = {
    /* ── The series: Imperiul Umbrelor ────────────────────────────────── */
    series: [
      {
        id: 'speranta',
        ratio: 0.770,
        title: 'SPERANȚA ARE NUMELE LUI',
        price: 75,
        status: 'DISPONIBIL',
        statusClass: 'in',
        badge: null,
        img: 'assets/img/client/cover-speranta.jpg',
        desc: 'Vol. 1 Imperiul Umbrelor'
      },
      {
        id: 'pacatul',
        ratio: 0.442,
        title: 'PĂCATUL CASEI VANNI: UMBRA LUI VESPER',
        price: 85,
        status: 'PRECOMANDĂ',
        statusClass: 'pre',
        badge: 'PRECOMANDĂ • 16 FEB',
        img: 'assets/img/client/cover-paculul.jpg',
        desc: 'Vol. 2 - Lansare 16 februarie'
      },
      {
        id: 'mostenirea',
        ratio: 0.630,
        title: 'MOȘTENIREA SINISTRĂ',
        price: 0,
        status: 'ÎN CURÂND',
        statusClass: 'soon',
        badge: null,
        img: 'assets/img/client/cover-mostenirea.jpg',
        desc: 'Vol. 3 Imperiul Umbrelor'
      },
      {
        id: 'exilul',
        title: 'EXILUL SÂNGELUI',
        price: 0,
        status: 'ÎN CURÂND',
        statusClass: 'soon',
        badge: null,
        img: 'assets/img/client/cover-exilul.jpg',
        desc: 'Vol. 4 Imperiul Umbrelor'
      }
    ],

    /* ── The standalone titles ─────────────────────────────────────────── */
    standalone: [
      {
        id: 'a94',
        title: 'PROIECTUL A-94 CODUL SÂNGELUI',
        price: 80,
        img: 'assets/img/client/cover-a94.jpg'
      },
      {
        id: 'sprayed',
        title: 'SPERANȚA ARE NUMELE LUI EDIȚIE SPRAYED EDGES',
        price: 70,
        img: 'assets/img/client/speranta-stack.jpg'
      }
    ],

    /* ── The reader reviews ────────────────────────────────────────────── */
    reviews: {
      speranta: [
        { id: 'r1', name: 'Maria D.', stars: 5, text: 'Am plâns și am iubit fiecare pagină. Rian e tot ce nu știam că vreau.' },
        { id: 'r2', name: 'Andreea', stars: 5, text: 'Nu e romance dulce, e cuțit. Exact cum îmi place.' },
        { id: 'r3', name: 'Ioana P.', stars: 5, text: 'Speranța m-a ținut trează până la 3 dimineața. Nu am putut lăsa cartea.' }
      ],
      pacatul: [
        { id: 'r4', name: 'Elena M.', stars: 5, text: 'Vesper e periculos și perfect. Am nevoie de vol 2 acum, nu pot aștepta.' },
        { id: 'r5', name: 'Diana R.', stars: 5, text: 'Casa Vanni nu iartă. Eu nu pot uita ce am citit aici.' },
        { id: 'r6', name: 'Cristina V.', stars: 4, text: 'Dark, intens, cu suflet. Precomanda a meritat fiecare leu.' }
      ],
      mostenirea: [
        { id: 'r7', name: 'Cristina L.', stars: 5, text: 'Abia aștept. Dacă e ca primele două, o să doară frumos.' },
        { id: 'r8', name: 'Larisa', stars: 4, text: 'Te rog nu ne lăsa în suspans prea mult. Vreau continuarea.' }
      ],
      exilul: [
        { id: 'r9', name: 'Mihaela S.', stars: 5, text: 'Exilul sângelui sună ca o promisiune. Sunt gata să intru.' },
        { id: 'r10', name: 'Anca T.', stars: 5, text: 'Vreau să știu ce pierde ca să păstreze ce iubește. Sună sfâșietor.' }
      ],
      a94: [
        { id: 'r11', name: 'Teodora B.', stars: 5, text: 'Codul sângelui m-a prins de la prima pagină. Alt nivel de dark romance.' },
        { id: 'r12', name: 'Roxana', stars: 5, text: 'Dark, inteligent, cu suflet. A-94 e favorit standalone pentru mine.' },
        { id: 'r13', name: 'Mara I.', stars: 4, text: 'Proiectul ăsta e cuțit cu mânuși de mătase. Genial.' }
      ],
      sprayed: [
        { id: 'r14', name: 'Alexia D.', stars: 5, text: 'Ediția asta e artă. Marginile roșii ca o rană. Superbă.' },
        { id: 'r15', name: 'Sorina M.', stars: 5, text: 'Colecția mea merita asta. Calitate impecabilă, merită fiecare leu.' },
        { id: 'r16', name: 'Delia', stars: 5, text: 'Țin cartea și nu vreau să o deschid de frică să nu o murdăresc.' }
      ]
    },

    /* ── The journal ───────────────────────────────────────────────────── */
    posts: [
      {
        tag: 'PRECOMANDĂ DESCHISĂ',
        date: '16 FEBRUARIE • BLOG',
        title: 'S-a deschis precomanda pentru Păcatul Casei Vanni',
        excerpt: 'Vesper se întoarce. Am deschis lista pentru primele 100 de exemplare semnate cu sprayed edges roșu sângeriu. Livrare din 20 februarie.',
        img: 'assets/img/client/thumb-paculul.jpg',
        href: 'serie.html'
      },
      {
        tag: 'CULISE',
        date: '28 IANUARIE • BLOG',
        title: 'Cum a luat naștere Speranța Are Numele Lui',
        excerpt: 'A început cu o frază scrisă la 2 dimineața. Nu știam cine e el. Știam doar că ea îl așteaptă de 7 ani și nu mai are nimic de pierdut.',
        img: 'assets/img/client/thumb-speranta.jpg',
        href: 'serie.html'
      },
      {
        tag: 'EDIȚIE',
        date: '10 IANUARIE • BLOG',
        title: 'Ce înseamnă ediția sprayed edges și de ce am ales-o',
        excerpt: 'Marginile pictate manual, roșu închis, ca o rană care nu se închide. Pentru că poveștile astea nu sunt curate. Sunt vii.',
        img: 'assets/img/client/speranta-stack.jpg',
        href: 'serie.html'
      }
    ],

    /* ── Contact ───────────────────────────────────────────────────────── */
    contact: {
      email: 'cristiana.ciocan@gmail.com',
      orders: 'contact@cristiana-ciocan.ro',
      instagram: 'Instagram @christiana.ciocan',
      threads: 'Threads @chris.inksins',
      phone: '+40 7XX XXX XXX (placeholder)'
    },

    /* ── Author ────────────────────────────────────────────────────────── */
    author: {
      name: 'Christiana Ciocan',
      badge: 'AUTOARE 2026',
      stamp: 'FOTOGRAFIE • 2026',
      portrait: 'assets/img/client/author-portrait.jpg',
      paragraphs: [
        'Am 30 de ani și două vieți. Una se trezește dimineața cu o cană în mână și doi copii care mă strigă din cealaltă cameră. Le fac clătite, le leg școlarilor la curte și beau cafeaua rece, de obicei. Cealaltă viață se trezește noaptea, când casa tace, și scrie despre bărbați care nu știu să iubească frumos, dar învață. Despre femei care nu fug, dar tac. Și despre dragostea ca locul unde se decid lucruri pe care nu le poți da înapoi.',
        'Scriu dark romance cu suflet mafia, pentru că acolo am găsit adevărul despre loialitate. Despre cum dragostea poate fi și armă, și scut. Despre bărbații care ar mor pentru femeia lor și femeile care se prefac că n-au nevoie de ei.',
        'Imperiul Umbrelor nu e doar o serie. E locul unde mi-am pus toate fricile și toate curajele. Și te invit să intri. Dar să știi: odată ce intri, nu mai ieși la fel.'
      ],
      signature: 'SEMNEZ CU SÂNGE ȘI SPERANȚĂ'
    },

    /* ── Page copy ─────────────────────────────────────────────────────── */
    copy: {
      welcomeEyebrow: 'BINE AȚI VENIT ÎN IMPERIUL MEU',
      welcomeTitle: 'Aici, poveștile nu se termină cum te aștepți.',
      welcomeScript: 'Încep acolo unde alții se opresc.',
      welcomeLede: 'Mă bucur că ești aici. Ia-ți o cafea, așază-te. Am să-ți arăt lumea în care iubirea doare frumos.',

      seriesEyebrow: 'O SAGĂ MAFIA DARK ROMANCE',
      seriesTitle: 'SERIA IMPERIUL UMBRELOR',
      standaloneTitle: 'STANDALONE',
      standaloneLede: 'Două lumi separate de Imperiu, dar cu același sânge: iubiri care nu cer voie.',

      blogTitle: 'JURNALUL IMPERIULUI',
      blogEyebrow: 'ANUNȚURI, PRECOMENZI, SECRETE DIN CULISE',
      blogLede: 'Tot ce se întâmplă în spatele cărților, înainte să ajungă la tine.',

      futureTitle: 'PROIECTE VIITOARE',
      futureLede: 'În curând vor fi afișate aici noile manuscrise din Imperiu. Lucrez la ceva ce o să vă țină treji noaptea. Rămâneți aproape.',
      futureTag: 'MANUSCRIS ÎN LUCRU',
      futureAccess: 'ACCES RESTRICȚIONAT',
      futureConfidential: 'CONFIDENȚIAL',

      gateSeriesTag: 'DARK ROMANCE • MAFIA',
      gateSeriesTitle: 'INTRĂ ÎN IMPERIUL UMBRELOR',
      gateSeriesLede: 'Patru volume. O familie. Prețuri care cresc pe măsură ce se închid porțile.',
      gateStandaloneTag: 'POVEȘTI UNICE',
      gateStandaloneTitle: 'VEZI STANDALONE',
      gateStandaloneLede: 'Proiecte care nu aparțin Imperiului, dar vin din același sânge.',
      gateBlogTag: 'ANUNȚURI & SECRETE',
      gateBlogTitle: 'CITEȘTE JURNALUL',
      gateBlogLede: 'Precomenzi, culise și tot ce se întâmplă înainte să ajungă la cititor.',

      reviewsTitle: 'CE SPUN CITITORII',
      contactLede: 'Pentru comenzi, colaborări, autografe — scrie-mi. Răspund personal la fiecare mesaj.',
      footerTag: 'Dark romance pentru cititori care iubesc povestea cu dinți. Scris noaptea, în ploaie, cel mai des cu prea multă cafea.',
      footerCopy: '© 2026 CHRISTIANA CIOCAN • IMPERIUL UMBRELOR ®'
    }
  };

  global.NocturneClient = CLIENT;
})(window);
