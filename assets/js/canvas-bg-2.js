class DynamicBackground {
    constructor() {
      this.canvas = null;
      this.ctx = null;
      this.container = null;
      this.animationId = null;
      this.mouse = { x: 0, y: 0 };
      this.time = 0;
      this.orbs = [];
      this.particles = [];
      this.geometricShapes = [];
      this.lightRays = [];
      this.circularWaves = [];
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
      this.createOrbs();
      this.createParticles();
      this.createGeometricShapes();
      this.createLightRays();
      this.createCircularWaves();
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

    createParticles() {
      this.particles = [];
      const particleCount = window.innerWidth < 768 ? 20 : 35;
      
      for (let i = 0; i < particleCount; i++) {
        this.particles.push({
          x: Math.random() * this.canvas.width,
          y: Math.random() * this.canvas.height,
          vx: (Math.random() - 0.5) * 0.5,
          vy: (Math.random() - 0.5) * 0.5,
          size: Math.random() * 2 + 1,
          opacity: Math.random() * 0.4 + 0.1,
          hue: Math.random() * 60 + 180,
          life: Math.random() * 1000 + 500,
          maxLife: Math.random() * 1000 + 500
        });
      }
    }

    createGeometricShapes() {
      this.geometricShapes = [];
      const shapeCount = window.innerWidth < 768 ? 3 : 5;
      
      for (let i = 0; i < shapeCount; i++) {
        this.geometricShapes.push({
          x: Math.random() * this.canvas.width,
          y: Math.random() * this.canvas.height,
          size: Math.random() * 30 + 20,
          rotation: Math.random() * Math.PI * 2,
          rotationSpeed: (Math.random() - 0.5) * 0.02,
          sides: Math.floor(Math.random() * 4) + 3, // 3-6 sides
          opacity: Math.random() * 0.1 + 0.05,
          hue: Math.random() * 40 + 200,
          pulseSpeed: Math.random() * 0.003 + 0.001,
          pulseOffset: Math.random() * Math.PI * 2
        });
      }
    }

    createLightRays() {
      this.lightRays = [];
      const rayCount = 4;
      
      for (let i = 0; i < rayCount; i++) {
        this.lightRays.push({
          angle: (i / rayCount) * Math.PI * 2,
          length: Math.random() * 200 + 100,
          width: Math.random() * 3 + 1,
          opacity: Math.random() * 0.15 + 0.05,
          speed: Math.random() * 0.01 + 0.005,
          offset: Math.random() * Math.PI * 2,
          hue: Math.random() * 30 + 45
        });
      }
    }

    createCircularWaves() {
      this.circularWaves = [];
      const waveCount = 2;
      
      for (let i = 0; i < waveCount; i++) {
        this.circularWaves.push({
          x: Math.random() * this.canvas.width,
          y: Math.random() * this.canvas.height,
          radius: 0,
          maxRadius: Math.random() * 150 + 100,
          speed: Math.random() * 0.8 + 0.3,
          opacity: Math.random() * 0.08 + 0.02,
          hue: Math.random() * 40 + 120,
          thickness: Math.random() * 2 + 1,
          life: 0,
          maxLife: Math.random() * 200 + 150
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
      
      this.createOrbs();
      this.createParticles();
      this.createGeometricShapes();
      this.createLightRays();
      this.createCircularWaves();
    }
  
    drawBackground() {
      // Base gradient
      const gradient = this.ctx.createLinearGradient(0, 0, 0, this.canvas.height);
      gradient.addColorStop(0, '#fafafa');
      gradient.addColorStop(1, '#f8fafc');
      this.ctx.fillStyle = gradient;
      this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
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

    drawParticles() {
      this.particles.forEach(particle => {
        // Update position
        particle.x += particle.vx;
        particle.y += particle.vy;
        particle.life--;
        
        // Reset particle when it dies
        if (particle.life <= 0) {
          particle.x = Math.random() * this.canvas.width;
          particle.y = Math.random() * this.canvas.height;
          particle.life = particle.maxLife;
        }
        
        // Boundary wrapping
        if (particle.x < 0) particle.x = this.canvas.width;
        if (particle.x > this.canvas.width) particle.x = 0;
        if (particle.y < 0) particle.y = this.canvas.height;
        if (particle.y > this.canvas.height) particle.y = 0;
        
        // Mouse interaction
        const dx = this.mouse.x - particle.x;
        const dy = this.mouse.y - particle.y;
        const distance = Math.sqrt(dx * dx + dy * dy);
        
        if (distance < 80) {
          const force = (80 - distance) / 80 * 0.05;
          particle.vx += dx * force * 0.001;
          particle.vy += dy * force * 0.001;
        }
        
        // Draw particle
        this.ctx.beginPath();
        this.ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
        this.ctx.fillStyle = `hsla(${particle.hue}, 70%, 60%, ${particle.opacity})`;
        this.ctx.fill();
      });
    }

    drawGeometricShapes() {
      this.geometricShapes.forEach(shape => {
        // Update rotation
        shape.rotation += shape.rotationSpeed;
        
        // Pulsing effect
        const pulse = Math.sin(this.time * shape.pulseSpeed + shape.pulseOffset) * 0.3 + 0.7;
        const currentSize = shape.size * pulse;
        const currentOpacity = shape.opacity * pulse;
        
        this.ctx.save();
        this.ctx.translate(shape.x, shape.y);
        this.ctx.rotate(shape.rotation);
        
        // Draw polygon
        this.ctx.beginPath();
        for (let i = 0; i < shape.sides; i++) {
          const angle = (i / shape.sides) * Math.PI * 2;
          const x = Math.cos(angle) * currentSize;
          const y = Math.sin(angle) * currentSize;
          
          if (i === 0) {
            this.ctx.moveTo(x, y);
          } else {
            this.ctx.lineTo(x, y);
          }
        }
        this.ctx.closePath();
        
        this.ctx.strokeStyle = `hsla(${shape.hue}, 60%, 70%, ${currentOpacity})`;
        this.ctx.lineWidth = 2;
        this.ctx.stroke();
        
        this.ctx.restore();
      });
    }

    drawLightRays() {
      this.lightRays.forEach(ray => {
        // Update angle
        ray.angle += ray.speed;
        
        const centerX = this.canvas.width / 2;
        const centerY = this.canvas.height / 2;
        
        const endX = centerX + Math.cos(ray.angle + ray.offset) * ray.length;
        const endY = centerY + Math.sin(ray.angle + ray.offset) * ray.length;
        
        // Create gradient for ray
        const gradient = this.ctx.createLinearGradient(centerX, centerY, endX, endY);
        gradient.addColorStop(0, `hsla(${ray.hue}, 80%, 80%, ${ray.opacity})`);
        gradient.addColorStop(1, `hsla(${ray.hue}, 80%, 80%, 0)`);
        
        this.ctx.beginPath();
        this.ctx.moveTo(centerX, centerY);
        this.ctx.lineTo(endX, endY);
        this.ctx.strokeStyle = gradient;
        this.ctx.lineWidth = ray.width;
        this.ctx.stroke();
      });
    }

    drawCircularWaves() {
      this.circularWaves.forEach(wave => {
        // Update wave
        wave.radius += wave.speed;
        wave.life++;
        
        // Reset wave when it reaches max radius or life
        if (wave.radius > wave.maxRadius || wave.life > wave.maxLife) {
          wave.x = Math.random() * this.canvas.width;
          wave.y = Math.random() * this.canvas.height;
          wave.radius = 0;
          wave.life = 0;
        }
        
        // Calculate opacity based on life
        const lifeRatio = wave.life / wave.maxLife;
        const currentOpacity = wave.opacity * (1 - lifeRatio);
        
        // Draw circular wave
        this.ctx.beginPath();
        this.ctx.arc(wave.x, wave.y, wave.radius, 0, Math.PI * 2);
        this.ctx.strokeStyle = `hsla(${wave.hue}, 60%, 70%, ${currentOpacity})`;
        this.ctx.lineWidth = wave.thickness;
        this.ctx.stroke();
      });
    }

    animate() {
      this.time += 0.5;
      
      this.drawBackground();
      this.drawOrbs();
      this.drawParticles();
      this.drawGeometricShapes();
      this.drawLightRays();
      this.drawCircularWaves();
      
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