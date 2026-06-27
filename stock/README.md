# NSE Stock Analyzer — Complete Guide

**Free. No API key. No subscription. Runs on your own Apache server.**
Data comes from Yahoo Finance (free, real-time delayed 15 min).

---

## What This App Does

This is a full technical analysis dashboard for NSE (National Stock Exchange) India stocks.
It fetches live price data, runs all major technical indicators in pure PHP,
detects chart patterns, scores momentum, and tells you whether a stock is a Buy, Sell, or Hold —
with the exact reason why.

---

## The 5 Tabs

### 1. 📊 Watchlist
Your main dashboard. Loads 15–20 NSE stocks automatically every 5 minutes.

**What you see per stock:**
- Live price + today's change % + 5-day change %
- Momentum Score (−100 to +100) with a visual bar
- Direction: 🚀 Rising / 📉 Falling / ➡ Flat
- Volume: how much above/below average (e.g. 🔥 2.3x vol = institutional activity)
- RSI (14) — overbought/oversold
- Supertrend — trend direction
- ADX/DMI — how strong the trend is (above 25 = strong trend)
- Stochastic — overbought/oversold with %K and %D
- OBV — whether big money is accumulating or distributing
- Signal: Buy / Sell / Hold with confidence %
- Chart pattern detected (e.g. Bullish Engulfing, Double Bottom)
- ATR-based Target price and Stop Loss
- Pivot Points: PP, R1, S1 (yesterday's levels)
- Action buttons: Deep Analyze → and 🔔 Alert

**Buy/Sell table:**
Stocks are split into two tables — Buy candidates (positive momentum, top) and
Sell/Avoid candidates (negative momentum, bottom). Both ranked by momentum score.

**Custom Watchlist:**
Add any NSE symbol using the + Add box above the table.
Remove with the × button. Saved permanently to storage/watchlist.json.
Click "Reset Default" to go back to the built-in 15 stocks.

---

### 2. 🔍 Analyze (Deep Dive on Any Stock)

**This is where you go when you want to know everything about a stock.**

Type any NSE symbol (e.g. RELIANCE, TCS, INFY, SBIN) and click Analyze.
You get a full page breakdown:

**Price Summary**
- Current price, today's change, 52-week high/low
- Big signal badge: 🟢 Buy / 🔴 Sell / 🟡 Hold with confidence %

**Summary Verdict**
- Plain English explanation of what the stock is doing right now

**Chart Patterns Detected**
- Candlestick patterns: Doji, Hammer, Shooting Star, Bullish/Bearish Engulfing,
  Morning Star, Evening Star, Bullish/Bearish Marubozu, Piercing Line, Dark Cloud Cover
- Chart patterns: Double Top, Double Bottom, Bull Flag, Head & Shoulders

**Technical Indicators — all computed from real OHLCV data:**

| Indicator | What It Tells You |
|---|---|
| EMA 20 & 50 | Short and medium term trend. Golden Cross = bullish, Death Cross = bearish |
| RSI (14) | Momentum. Below 30 = oversold (potential buy), above 70 = overbought (potential sell) |
| MACD (12,26,9) | Trend direction and strength. Histogram above 0 = bullish |
| Bollinger Bands | Volatility. Price near lower band = oversold, near upper = overbought |
| Supertrend (10,3) | Trend following. Green = uptrend, Red = downtrend |
| VWAP | Intraday fair value. Price above = buyers in control, below = sellers |
| ATR (14) | Volatility measure. Used for target and stop loss calculation |
| ADX / DMI | Trend strength. ADX > 25 = strong trend. +DI > -DI = bullish |
| Stochastic (14,3) | %K and %D. Below 20 = oversold, above 80 = overbought |
| OBV | On Balance Volume. Rising = accumulation (smart money buying) |
| Support | Lowest price in last 20 bars |
| Resistance | Highest price in last 20 bars |

**Bullish Factors vs Bearish Factors**
- Every bullish signal listed individually (e.g. "RSI oversold at 28 — potential bounce")
- Every bearish signal listed individually (e.g. "Death Cross: EMA20 below EMA50")
- Composite score showing net bull vs bear weight

**Trade Setup (ATR-based)**
- Entry: current price
- Target 1: entry + 1.5× ATR
- Target 2: entry + 3× ATR
- Stop Loss: entry − 1× ATR
- Risk:Reward ratio

**Fundamental Data (from Yahoo Finance)**
- P/E ratio, P/B ratio, Market Cap (Large/Mid/Small)
- Debt/Equity ratio, Return on Equity
- Sector and Industry

**Quick picks below the search box** — click any to instantly analyze:
RELIANCE, TCS, INFY, HDFCBANK, ICICIBANK, BAJFINANCE, AXISBANK, WIPRO,
TATAMOTORS, SUNPHARMA, MARUTI, LT, TITAN, KOTAKBANK, SBIN

**Recent searches** are saved in the session so you can quickly re-run.

---

### 3. 📉 Chart (Intraday)

Live price chart using Chart.js with volume bars below.

- Choose interval: 5 min, 15 min, or 1 hour
- Price line chart (green if up, red if down) with fill
- Volume bars (green = up candle, red = down candle)
- Day stats: last price, change %, high, low, open, total volume
- Full Pivot Point table: PP, R1, R2, R3, S1, S2, S3, plus CPR (TC and BC)

Click "Analyze →" on any watchlist stock to jump straight to its intraday chart.

---

### 4. 📰 News

Live market news from:
- Yahoo Finance RSS (NSE/NIFTY headlines)
- Economic Times Markets RSS

Refreshes every 10 minutes. Click any card to open the full article.

---

### 5. 🏆 Leaders (Signal Leaderboard)

Tracks which stocks are accumulating the most Buy or Sell signals over time.
Updated every minute via the per-minute tick.

- Live price strip showing all watchlist stocks at a glance
- Buy Leaders: stocks with the most Buy signals in this session
- Sell Leaders: stocks with the most Sell signals
- Click "Tick Now" to force an immediate update
- Auto-ticks every 60 seconds when this tab is active

---

## Price Alerts

On any watchlist row click **🔔 Alert** and type:
- `above 2500` — alerts when price crosses above ₹2500
- `below 1200` — alerts when price drops below ₹1200

Alerts are checked every 60 seconds. You get a browser notification
(if you allow it) or a popup alert when triggered.
All alerts saved in `storage/alerts.json`.

---

## How to Get Full Analysis on Any Stock

1. Go to the **🔍 Analyze tab**
2. Type the NSE symbol — e.g. `RELIANCE` or `HDFCBANK`
3. Press Enter or click **Analyze →**
4. Wait 10–20 seconds while it fetches 90 days of data and computes all indicators
5. Read the verdict, check the bullish/bearish factors, and see the trade setup

You can also click **"Analyze →"** or **"Deep Analysis →"** next to any stock
in the Watchlist table to jump straight to its full analysis.

**Supported symbols:** Any stock on NSE. Add `.NS` suffix if auto-detection fails.
Examples: `RELIANCE.NS`, `TCS.NS`, `^NSEI` (Nifty 50 index)

---

## Setup (Deploy on Apache)

```bash
# 1. Copy to web root
sudo cp -r stock_v3_upgraded /var/www/html/stock

# 2. Permissions
sudo chown -R www-data:www-data /var/www/html/stock
sudo chmod 775 /var/www/html/stock/storage

# 3. Enable mod_rewrite
sudo a2enmod rewrite && sudo systemctl restart apache2

# 4. Make sure your Apache config has AllowOverride All
# In /etc/apache2/apache2.conf, <Directory /var/www/>:
#   AllowOverride All

# 5. Edit credentials
nano /var/www/html/stock/.env
```

**Default login:** admin / stockpass123 (change in `.env`)

**Visit:** http://localhost/stock/

---

## Auto-Refresh (Cron)

Instead of the frontend tick, you can run the signal logger via cron:

```bash
# Edit crontab
crontab -e

# Add this line (runs every minute):
* * * * * curl -s "http://localhost/stock/api/cron?key=YOUR_CRON_KEY" > /dev/null 2>&1
```

Set `CRON_KEY=YOUR_CRON_KEY` in `.env` (change from the default `changeme`).

---

## What's Computed From Real Data vs Estimates

| Data | Source | Notes |
|---|---|---|
| Live price, change %, volume | Yahoo Finance v8 | 15-min delayed |
| 90-day OHLCV history | Yahoo Finance v8 | Used for all indicators |
| EMA, RSI, MACD, BB, Supertrend | Computed in PHP | From real daily closes |
| Stochastic, ADX, OBV | Computed in PHP | From real OHLCV |
| VWAP | Approximated from daily | Not tick-level VWAP |
| Pivot Points | From previous day OHLC | Standard formula |
| Intraday candles | Yahoo Finance v8 | 5m/15m/1h real candles |
| P/E, P/B, Market Cap | Yahoo Finance quote | From Yahoo fundamentals |
| News | Yahoo Finance + ET RSS | Free RSS feeds |

---

## Disclaimer

This tool is for **educational and monitoring purposes only**.
It is not financial advice. Technical analysis does not guarantee future results.
Always use stop losses and consult a SEBI-registered investment advisor before trading.
The developer is not responsible for any trading losses.
