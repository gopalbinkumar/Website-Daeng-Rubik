(() => {
  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

  // Mobile drawer
  const drawer = $('#mobileDrawer');
  const backdrop = $('#drawerBackdrop');
  const openBtn = $('#openDrawer');
  const closeBtn = $('#closeDrawer');
  const setDrawer = (open) => {
    if (!drawer || !backdrop) return;
    drawer.classList.toggle('open', open);
    backdrop.classList.toggle('open', open);
    document.body.style.overflow = open ? 'hidden' : '';
  };
  openBtn?.addEventListener('click', () => setDrawer(true));
  closeBtn?.addEventListener('click', () => setDrawer(false));
  backdrop?.addEventListener('click', () => setDrawer(false));
  $$('#mobileDrawer a').forEach(a => a.addEventListener('click', () => setDrawer(false)));

  // Tabs (Event & Belajar): buttons with [data-tabs] container, [data-tab] items, [data-panel] panels
  $$('[data-tabs]').forEach((tabsRoot) => {
    const tabs = $$('[data-tab]', tabsRoot);
    const panels = $$('[data-panel]', tabsRoot);
    const activate = (key) => {
      tabs.forEach(t => t.classList.toggle('active', t.dataset.tab === key));
      panels.forEach(p => p.style.display = (p.dataset.panel === key) ? '' : 'none');
    };
    tabs.forEach(t => t.addEventListener('click', () => activate(t.dataset.tab)));
    const initial = tabs.find(t => t.classList.contains('active'))?.dataset.tab || tabs[0]?.dataset.tab;
    if (initial) activate(initial);
  });

  // Mobile filter sheet (Products)
  const openFilter = $('#openFilter');
  const closeFilter = $('#closeFilter');
  const sheet = $('#filterSheet');
  const sheetBackdrop = $('#sheetBackdrop');
  const setSheet = (open) => {
    if (!sheet || !sheetBackdrop) return;
    sheet.classList.toggle('open', open);
    sheetBackdrop.classList.toggle('open', open);
    document.body.style.overflow = open ? 'hidden' : '';
  };
  openFilter?.addEventListener('click', () => setSheet(true));
  closeFilter?.addEventListener('click', () => setSheet(false));
  sheetBackdrop?.addEventListener('click', () => setSheet(false));
})();

