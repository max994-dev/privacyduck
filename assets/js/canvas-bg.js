class DynamicBackground {
    constructor() {
      this.canvas = null;
      this.ctx = null;
      this.container = null;
      this.animationId = null;
      this.mouse = { x: 0, y: 0 };
      this.time = 0;
      this.waves = [];
      this.orbs = [];
      this.init();
    }
  
    init() {
      // Wait for DOM to be ready
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => this.setup());
      } else {
        this.setup();
      }
    }

    setup() {
      this.container = document.querySelector('.pb-\\[120px\\]');
      if (!this.container) {
        console.error('Container not found');
        return;
      }
      
      this.createCanvas();
      this.createWaves();
      this.createOrbs();
      this.bindEvents();
      this.animate();
    }
  
    createCanvas() {
      // Create canvas element
      this.canvas = document.createElement('canvas');
      this.canvas.id = 'dynamic-background';
      this.canvas.style.position = 'absolute';
      this.canvas.style.top = '0';
      this.canvas.style.left = '0';
      this.canvas.style.width = '100%';
      this.canvas.style.height = '100%';
      this.canvas.style.zIndex = '0';
      this.canvas.style.pointerEvents = 'none';
      
      // Make container relative
      this.container.style.position = 'relative';
      
      // Insert canvas as first child
      this.container.insertBefore(this.canvas, this.container.firstChild);
      
      this.ctx = this.canvas.getContext('2d');
      this.resize();
    }
  
    createWaves() {
      this.waves = [];
      for (let i = 0; i < 3; i++) {
        this.waves.push({
          amplitude: 20 + i * 8,
          frequency: 0.008 + i * 0.003,
          speed: 0.05 + i * 0.02,
          offset: i * Math.PI / 2,
          y: this.canvas.height * (0.4 + i * 0.2),
          opacity: 0.08 - i * 0.02
        });
      }
    }
  
    createOrbs() {
      this.orbs = [];
      const orbCount = window.innerWidth < 768 ? 4 : 6;
      
      for (let i = 0; i < orbCount; i++) {
        this.orbs.push({
          x: Math.random() * this.canvas.width,
          y: Math.random() * this.canvas.height,
          radius: Math.random() * 40 + 15,
          vx: (Math.random() - 0.5) * 0.3,
          vy: (Math.random() - 0.5) * 0.3,
          opacity: Math.random() * 0.06 + 0.03,
          hue: Math.random() * 40 + 120,
          pulseSpeed: Math.random() * 0.002 + 0.001,
          pulseOffset: Math.random() * Math.PI * 2
        });
      }
    }
  
    bindEvents() {
      window.addEventListener('resize', () => this.resize());
      
      this.container.addEventListener('mousemove', (e) => {
        const rect = this.container.getBoundingClientRect();
        this.mouse.x = e.clientX - rect.left;
        this.mouse.y = e.clientY - rect.top;
      });
    }
  
    resize() {
      if (!this.container) return;
      
      const rect = this.container.getBoundingClientRect();
      this.canvas.width = rect.width;
      this.canvas.height = rect.height;
      
      this.createWaves();
      this.createOrbs();
    }
  
    drawBackground() {
      // Base gradient
      const gradient = this.ctx.createLinearGradient(0, 0, 0, this.canvas.height);
      gradient.addColorStop(0, '#fafafa');
      gradient.addColorStop(1, '#f8fafc');
      this.ctx.fillStyle = gradient;
      this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
    }
  
    drawWaves() {
      this.waves.forEach(wave => {
        this.ctx.beginPath();
        this.ctx.moveTo(0, wave.y);
        
        for (let x = 0; x <= this.canvas.width; x += 3) {
          const y = wave.y + 
            Math.sin(x * wave.frequency + this.time * wave.speed + wave.offset) * wave.amplitude;
          this.ctx.lineTo(x, y);
        }
        
        this.ctx.lineTo(this.canvas.width, this.canvas.height);
        this.ctx.lineTo(0, this.canvas.height);
        this.ctx.closePath();
        
        const waveGradient = this.ctx.createLinearGradient(0, wave.y - wave.amplitude, 0, this.canvas.height);
        waveGradient.addColorStop(0, `rgba(34, 197, 94, ${wave.opacity})`);
        waveGradient.addColorStop(1, `rgba(34, 197, 94, 0)`);
        
        this.ctx.fillStyle = waveGradient;
        this.ctx.fill();
      });
    }
  
    drawOrbs() {
      this.orbs.forEach(orb => {
        // Update position
        orb.x += orb.vx;
        orb.y += orb.vy;
        
        // Mouse interaction
        const dx = this.mouse.x - orb.x;
        const dy = this.mouse.y - orb.y;
        const distance = Math.sqrt(dx * dx + dy * dy);
        
        if (distance < 100) {
          const force = (100 - distance) / 100 * 0.1;
          orb.vx += dx * force * 0.001;
          orb.vy += dy * force * 0.001;
        }
        
        // Boundary bounce
        if (orb.x < orb.radius || orb.x > this.canvas.width - orb.radius) {
          orb.vx *= -0.8;
          orb.x = Math.max(orb.radius, Math.min(this.canvas.width - orb.radius, orb.x));
        }
        if (orb.y < orb.radius || orb.y > this.canvas.height - orb.radius) {
          orb.vy *= -0.8;
          orb.y = Math.max(orb.radius, Math.min(this.canvas.height - orb.radius, orb.y));
        }
        
        // Pulsing effect
        const pulse = Math.sin(this.time * orb.pulseSpeed + orb.pulseOffset) * 0.2 + 0.8;
        const currentRadius = orb.radius * pulse;
        const currentOpacity = orb.opacity * pulse;
        
        // Draw orb
        const gradient = this.ctx.createRadialGradient(
          orb.x, orb.y, 0,
          orb.x, orb.y, currentRadius
        );
        gradient.addColorStop(0, `hsla(${orb.hue}, 60%, 70%, ${currentOpacity})`);
        gradient.addColorStop(0.6, `hsla(${orb.hue}, 60%, 70%, ${currentOpacity * 0.4})`);
        gradient.addColorStop(1, `hsla(${orb.hue}, 60%, 70%, 0)`);
        
        this.ctx.beginPath();
        this.ctx.arc(orb.x, orb.y, currentRadius, 0, Math.PI * 2);
        this.ctx.fillStyle = gradient;
        this.ctx.fill();
      });
    }
  
    animate() {
      this.time += 0.5;
      
      this.drawBackground();
      this.drawWaves();
      this.drawOrbs();
      
      this.animationId = requestAnimationFrame(() => this.animate());
    }
  
    destroy() {
      if (this.animationId) {
        cancelAnimationFrame(this.animationId);
      }
      if (this.canvas && this.canvas.parentNode) {
        this.canvas.parentNode.removeChild(this.canvas);
      }
    }
  }
  
  // Initialize when DOM is loaded
  let backgroundInstance = null;
  
  function initBackground() {
    if (backgroundInstance) {
      backgroundInstance.destroy();
    }
    backgroundInstance = new DynamicBackground();
  }
  
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initBackground);
  } else {
    initBackground();
  }